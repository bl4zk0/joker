<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class CheckLocale
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
        $locale = App::getLocale();

        if ($request->query('lang') !== null) {
            if ($request->query('lang') === 'ka') {
                $locale = 'ka';
            } elseif ($request->query('lang') === 'en') {
                $locale = 'en';
            }
            Cookie::queue('lang', $locale, 10080, '/', '', '', false);
        } elseif (! $request->hasCookie('lang')) {
            Cookie::queue('lang', $locale, 10080, '/', '', '', false);
        } else {
            if ($request->cookie('lang') === 'ka') {
                $locale = 'ka';
            } elseif ($request->cookie('lang') === 'en') {
                $locale = 'en';
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}