<?php

namespace App\Http\Controllers;

use App\Events\CardPlayEvent;
use App\Events\CardsEvent;
use App\Events\UpdateGameEvent;
use App\Events\UpdateGamesEvent;
use App\Game;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function start(Game $game)
    {
        //$this->authorize('update', $game);
        if(! $game->creator->id == auth()->id()) return abort(403, 'not allowed');
        $game->start();
    }

    public function trump(Request $request, Game $game)
    {
        $this->authorize('trump', $game);
        $card = [
            'strength' => $request->strength,
            'suit' => $request->suit,
        ];

        $game->update([
            'state' => 'call',
            'trump' => $card
        ]);

        $game->players->each(function ($player, $pos) {
            broadcast(new CardsEvent($player->id, $player->cards));
        });

        broadcast(new UpdateGameEvent($game));
    }

    public function call(Request $request, Game $game)
    {
        $this->authorize('call', $game);
        // TODO: validate call here...
        auth()->user()->player->scores()->create([
            'quarter' => $game->quarter,
            'call' => $request->call
        ]);
        // TODO: check for duplicate database queries get rid of unnecessary queries
        $game->updateTurn();
        $game->updateCallCount();
        broadcast(new UpdateGameEvent($game));
    }

    public function card(Request $request, Game $game)
    {
        // aq aseve unda shevamocmot tu sheudzlia am kartis tamashi;;; validacia da ase shemdeg////
        $this->authorize('card', $game);
        // check if it is valid card validate request ar dagvavicydes jokeris validacia es mnishvnelovania;;
        $card = [
            'strength' => $request->strength,
            'suit' => $request->suit,
        ];

        $player = auth()->user()->player;
        $playerCards = $player->cards;
        $playerCard = array_search($card, $playerCards);
        array_splice($playerCards, $playerCard, 1);
        if (empty($playerCards)) {
            $player->cards = null;
        } else {
            $player->cards = $playerCards;
        }
        $player->save();

        if ($request->has('action')) {
            $card['action'] = $request->action;
            if ($request->has('actionsuit')) {
                $card['actionsuit'] = $request->actionsuit;
            }
        }

        broadcast (new CardPlayEvent($game->id, auth()->user()->player->position, $card));
        $game->addCard($card);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::latest()->where('state', '0')->get();

        return view('lobby', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $game = Game::create(['user_id' => auth()->id()]);

        broadcast(new UpdateGamesEvent());

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
            if (! $game->players()->pluck('user_id')->contains(auth()->id())) {

                $game->addPlayer(auth()->user());

                $game->refresh();

                broadcast(new UpdateGameEvent($game));

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Game $game
     * @return void
     */
    public function update(Request $request, Game $game)
    {
        $this->authorize('update', $game);
        $card = ['strength' => $request->strength, 'suit' => $request->suit];
        //update $game->cards.push
        //player remove card. update player->card
        // check if last calc vin caigo da tu bolo chamosvlaa gamovtvalot xeli da davarigot shemdegi
        //da aq vaaapdeitebt turns.... vnaxulobt merammdene darigebaa da is poziciaa pirveli -1 bolo

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
