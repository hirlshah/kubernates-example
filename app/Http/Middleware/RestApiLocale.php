<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RestApiLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(2);

        if(!in_array($locale, ['en', 'fr', 'es', 'cs'])) {
            abort(400);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
