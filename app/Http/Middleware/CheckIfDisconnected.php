<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class CheckIfDisconnected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $gameId = $request->user()->player->game_id;
            if ($gameId != null && ! Str::contains($request->path(), "games/$gameId")) {
                return redirect("games/$gameId");
            }
        }
        return $next($request);
    }
}
