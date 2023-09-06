<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale('en');
        
        $availLocale = ['en'=>'en', 'fr'=>'fr', 'es'=>'es', 'cs'=>'cs'];
        if(Auth::check()) {
            app()->setLocale(Auth::user()->lang);   
        } elseif(session()->has('locale') && array_key_exists(session()->get('locale'),$availLocale)){
            app()->setLocale(session()->get('locale'));
        }
        return $next($request);
    }
}