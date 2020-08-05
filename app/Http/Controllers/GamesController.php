<?php

namespace App\Http\Controllers;

use App\Events\CardDealEvent;
use App\Events\CardPlayEvent;
use App\Events\KickUserEvent;
use App\Events\PlayerCallEvent;
use App\Events\GetReadyEvent;
use App\Events\UpdateGameEvent;
use App\Events\UpdateLobbyEvent;
use App\Events\UpdateReadyEvent;
use App\Game;
use App\Jobs\BotJob;
use App\Jobs\ResetGameStart;
use App\Rules\CardRule;
use App\Rules\GamePasswordRule;
use App\Rules\KickUserRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GamesController extends Controller
{
    /**
     * @param Game $game
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function start(Game $game)
    {
        $this->authorize('start', $game);

        if (! ($game->players()->count() === 4)) {
            abort(406, 'Not enough players');
        }

        $game->update(['state' => 'ready', 'ready' => ['players' => [Auth::id()], 'count' => 1]]);

        ResetGameStart::dispatch($game)->delay(now()->addSeconds(13));

        broadcast(new GetReadyEvent($game->id, Auth::user()->player->position, '1'))->toOthers();

        return response([], 200);
    }

    public function ready(Request $request, Game $game)
    {
        $this->authorize('ready', $game);

        $request->validate(['ready' => ['required', 'boolean']]);

        $game->addReadyPlayer(Auth::id(), $request->ready);

        broadcast(new UpdateReadyEvent($game->id, Auth::user()->player->position, $request->ready))->toOthers();

        if ($game->ready['count'] == 4) {
            $game->start();
        }

    }

    /**
     * @param Request $request
     * @param Game $game
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function trump(Request $request, Game $game)
    {
        $this->authorize('trump', $game);

        $request->validate([
            'trump' => ['required', 'in:hearts,clubs,diamonds,spades,bez']
        ]);

        $strength = $request->trump == 'bez' ? 16 : 14;
        $trump = $request->trump == 'bez' ? 'black_joker' : $request->trump;

        $game->update([
            'state' => 'call',
            'trump' => ['suit' => $trump, 'strength' => $strength]
        ]);

        $game->players->each(function ($player) {
            broadcast(new CardDealEvent($player->user->id, $player->cards));
        });

        broadcast(new UpdateGameEvent($game));
    }

    /**
     * @param Request $request
     * @param Game $game
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function call(Request $request, Game $game)
    {
        $this->authorize('call', $game);

        $max = $game->numCardsToDeal();
        $except = $game->exceptCall();

        $request->validate([
            'call' => ['required', 'integer', 'min:0', "max:$max", "not_in:$except"]
        ]);
        $player = Auth::user()->player;
        $score = $player->scores()->create([
            'quarter' => $game->quarter,
            'call' => $request->call,
            'color' => 'white'
        ]);

        $game->updateTurn();
        $game->updateCallCount();

        broadcast(new PlayerCallEvent($game, $score, $player->position));

        if ($game->players[$game->turn]->disconnected) {
            BotJob::dispatch($game->players[$game->turn], $game)->delay(now()->addSeconds(1));
        }

//        return [
//            'score' => $score,
//            'state' => $game->state,
//            'turn' => $game->turn,
//        ];
    }

    /**
     * @param Request $request
     * @param Game $game
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function card(Request $request, Game $game)
    {
        $this->authorize('card', $game);

        $request->validate([
            'card' => ['required', 'array', new CardRule],
            'action' => ['required_if:card.strength,16', 'in:magali,caigos,mojokra,kvevidan'],
            'actionsuit' => ['required_if:action,magali,caigos', 'in:hearts,clubs,diamonds,spades']
        ]);

        $card = $request->card;

        if ($request->has('action')) {
            $card['action'] = $request->action;
            if ($request->action === 'magali' || $request->action === 'caigos') {
                $card['actionsuit'] = $request->actionsuit;
            }
        }

        $player = auth()->user()->player;

        if (! $player->canPlay($card, $game->cards, $game->trump)) {
            abort(422, "Don't bother, fool!");
        }

        $player->removeCard($request->card);

        $card = $game->addCard($card, $player);
        $checkTake = $game->checkTake();
        broadcast (new CardPlayEvent($game->id, $player->position, $card, $checkTake))->toOthers();

        if ($checkTake !== false) {
            $game->checkEndOfTheHand();
        }

        if ($game->players[$game->turn]->disconnected) {
            BotJob::dispatch($game->players[$game->turn], $game)->delay(now()->addSeconds(1));
        }
        //return $game->turn;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $games = Game::latest()->where('state', 'start')->get();

        return view('lobby', compact('games'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:1,9'],
            'penalty' => ['required', 'in:-200,-300,-400,-500,-900,-1000'],
            'password' => ['sometimes', 'accepted']
        ]);

        $password = $request->has('password') ? sprintf("%04d", rand(0, 9999)) : null;

        $game = Game::create([
            'type' => $request->type,
            'penalty' => $request->penalty,
            'user_id' => auth()->id(),
            'password' => $password
        ]);

        broadcast(new UpdateLobbyEvent());
        return redirect($game->path());
    }

    /**
     * Display the specified resource.
     *
     * @param Game $game
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, Game $game)
    {
        $game->makeVisible('password');

        $player = Auth::user()->player;
        $cards = $player->cards;
        $cards = $game->state == 'trump' ? array_slice($cards, 0, 3) : $cards;

        if ($game->players->contains($player)) {
            if ($player->disconnected) {
                $player->update(['disconnected' => false]);
            }
            return view('game', compact('game', 'cards'));
        }

        if ($game->password != null) {
            if (! $request->has('p')) {
                return view('enter_password');
            } else {
                $request->validate([
                    'p' => ['required', new GamePasswordRule($game->password)]
                ]);
            }
        }

        if ($game->state == 'start' && $game->players()->count() < 4) {

            $game->addPlayer(Auth::user());

            $game->refresh();

            broadcast(new UpdateGameEvent($game));
            broadcast(new UpdateLobbyEvent());

            return view('game', compact('game', 'cards'));

        } else {
            return redirect('/lobby');
        }
    }

    public function kick(Request $request, Game $game) {
        $this->authorize('kick', $game);

        $request->validate([
            'position' => ['required', new KickUserRule($game->players)]
        ]);

        $game->kick($request->position);

        broadcast(new KickUserEvent($game->id, $request->position, $game->players))->toOthers();

        return $game->players;
    }

    public function leave(Game $game)
    {
        $this->authorize('leave', $game);

        if ($game->state == 'start') {
            auth()->user()->player->update(['game_id' => null, 'position' => null]);
            $game->refresh();
            if (auth()->user()->is($game->creator) && $game->players()->count() > 0) {
                $game->update(['user_id' => $game->players[0]->user->id]);
                $game->reposition();
                broadcast(new UpdateGameEvent($game));
            } elseif ($game->players()->count() == 0) {
                $game->delete();
            } else {
                $game->reposition();
                broadcast(new UpdateGameEvent($game));
            }
            broadcast(new UpdateLobbyEvent());
            return;
        }

        if ($game->state != '0') {
            auth()->user()->player->update(['disconnected' => true]);
            return;
            // if all players leave subtract points delete game
        }
    }
}
