<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
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
        if (isset($_REQUEST['lang']) && !empty($_REQUEST['lang'])) {
            app()->setLocale($_REQUEST['lang']);
            session()->put('locale', $_REQUEST['lang']);
        }
        return $next($request);
    }
}
