<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminsController extends Controller
{
    public function start(Game $game)
    {
        if (! auth()->user()->isAdmin) return response([], 403);

        if (! ($game->players->count() === 4) || $game->state != 'start') {
            abort(400, '');
        }

        $game->start();
        return response('', 200);
    }

    public function cards(Request $request, Game $game)
    {
        if (! auth()->user()->isAdmin) return response([], 403);

        $validator = Validator::make($request->all(), [
            'position' => ['required', 'integer', 'min:0', 'max:3'],
            'cards' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $cards = [
                        ['strength' => 16, 'suit' => 'color_joker'],
                        ['strength' => 16, 'suit' => 'black_joker'],
                        ['strength' => 14, 'suit' => 'hearts'],
                        ['strength' => 14, 'suit' => 'clubs'],
                        ['strength' => 14, 'suit' => 'diamonds'],
                        ['strength' => 14, 'suit' => 'spades'],
                    ];

                    foreach($value as $card) {
                        if (! in_array($card, $cards)) {
                            $fail("Invalid $attribute");
                        }
                    }
                },
            ],
        ]);

        $validator->validate();

        $game->update(['admin_cards' => [
            'position' => $request->position,
            'cards' => array_unique($request->cards, SORT_REGULAR)
        ]]);

        return response([], 200);
    }
}
