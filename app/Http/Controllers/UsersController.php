<?php

namespace App\Http\Controllers;

use App\Events\PlayerJoinLeaveEvent;
use App\Events\UpdateLobbyEvent;
use App\Gravatar;
use App\Jobs\PlayerBotJob;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class UsersController extends Controller
{

    /**
     *  show user's profile
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('profile', compact('user'));
    }
    
    // for Pusher web hooks, the same is implemented for laravel-websockets
    // in JokerWebSocketHandler onClose method
    public function disconnected()
    {
        // environmental variable must be set
        $app_secret = env('PUSHER_APP_SECRET');

        $app_key = isset($_SERVER['HTTP_X_PUSHER_KEY']) ?: null;
        $webhook_signature = isset($_SERVER ['HTTP_X_PUSHER_SIGNATURE']) ?: null;
        
        if ($app_key === null or $webhook_signature === null) {
            return response('', 401);
        }

        $body = file_get_contents('php://input');

        $expected_signature = hash_hmac( 'sha256', $body, $app_secret, false );

        if($webhook_signature === $expected_signature) {
            // decode as associative array
            $payload = json_decode( $body, true );
            foreach($payload['events'] as &$event) {
                if ($event['name'] == 'member_removed') {
                    $user = User::find((int) $event['user_id']);
                    $player = $user->player;
                    $game = $player->game;

                    if ($game === null) {
                        return response('', 200);
                    }

                    if ($game->state == 'start' || $game->state == 'ready') {
                        $player->update(['game_id' => null, 'position' => null]);
                        $game->refresh();
                        if ($user->is($game->creator) && $game->players->count() > 0) {
                            $game->update(['user_id' => $game->players[0]->user->id]);
                            $game->reposition();
                            broadcast(new PlayerJoinLeaveEvent($game->id, $user->username, 'Left', $game->players, $game->user_id))->toOthers();
                        } elseif ($game->players->count() == 0) {
                            $game->delete();
                            return response('', 200);
                        } else {
                            $game->reposition();
                            broadcast(new PlayerJoinLeaveEvent($game->id, $user->username, 'Left', $game->players))->toOthers();
                        }
                        broadcast(new UpdateLobbyEvent());
                    } else {
                        if ($game->players()->where('disconnected', true)->count() == 3) {
                            $game->delete();
                            return response('', 200);
                        }

                        $player->update(['disconnected' => true]);
                        broadcast(new PlayerJoinLeaveEvent($game->id, $user->username, 'Left'))->toOthers();

                        if ($game->turn == $player->position) {
                            PlayerBotJob::dispatch($game->players[$game->turn], $game)->delay(now()->addSecond());
                        }
                    }
                }
            }

            return response('', 200);
        }
        else {
            return response('', 401);
        }
    }

    /**
     * show email change form
     */

    public function emailChangeForm()
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        return view('auth.email-change');
    }

    /**
     * show password change form
     */

    public function passwordChangeForm()
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        return view('auth.passwords.change');
    }

    /**
     * update user's email
     * @param Request $request
     */

    public function emailChange(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        $request->validate(['email' => ['required', 'string', 'email', 'max:255', 'unique:users']]);

        $user->update([
            'email' => $request->email,
            'email_verified_at' => null,
            'avatar_url' => Gravatar::url($request->email)
        ]);

        return redirect("/user/$user->id")->with('status', Lang::get('Email changed'));
    }

    /**
     * update user's password
     */

    public function passwordChange(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        $request->validate(['password' => ['required', 'string', 'min:8', 'confirmed']]);

        $user->update(['password' => Hash::make($request->password)]);

        return redirect("/user/$user->id")->with('status', Lang::get('Password changed'));
    }

}
