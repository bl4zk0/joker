<?php

namespace App\Http\Controllers;

use App\Events\CardPlayEvent;
use App\Events\CardsEvent;
use App\Events\UpdateGameEvent;
use App\Events\UpdateLobbyEvent;
use App\Game;
use App\Rules\CardRule;
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

        $game->start();
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

        $game->update([
            'state' => 'call',
            'trump' => $request->trump
        ]);

        $game->players->each(function ($player) {
            broadcast(new CardsEvent($player->id, $player->cards));
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

        auth()->user()->player->scores()->create([
            'quarter' => $game->quarter,
            'call' => $request->call
        ]);

        $game->updateTurn();
        $game->updateCallCount();

        broadcast(new UpdateGameEvent($game));
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
            'action' => ['required_if:card.strength,16', 'in:magali,caigos,mojokra,nije'],
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

        broadcast (new CardPlayEvent($game->id, auth()->user()->player->position, $card));

        $game->addCard($card);
        $game->checkTake();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $games = Game::latest()->where('state', '0')->get();

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
        $max = auth()->user()->player->rank;

        $request->validate([
            'rank' => ['required', 'integer', 'min:0', "max:$max"],
            'type' => ['required', 'in:1,9'],
            'penalty' => ['required', 'in:-200,-300,-400,-500,-900,-1000'],
            'password' => ['sometimes', 'accepted']
        ]);

        $password = $request->has('password') ? sprintf("%04d", rand(0, 9999)) : null;

        $game = Game::create([
            'rank' => $request->rank,
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
    public function show(Game $game)
    {
        // this whole thing has to be changed...
        if ($game->state == 0 && $game->players()->count() < 4) {
            if (! $game->players->contains(auth()->user()->player)) {

                $game->addPlayer(Auth::user());

                $game->refresh();

                broadcast(new UpdateGameEvent($game));
                broadcast(new UpdateLobbyEvent());

                return view('game', compact('game'));
            } else {
                // this is for development only. it should be removed
                //dd($game->players()->count());

                return view('game', compact('game'));
            }
        } elseif ($game->state != 0 && $game->players()->pluck('user_id')->contains(auth()->id())) {
            // probably also update disconnected state
            return view('game', compact('game'));
        } else {
            return view('game', compact('game'));
            return redirect('/lobby');
        }
    }
}
