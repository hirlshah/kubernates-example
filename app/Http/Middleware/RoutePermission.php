<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoutePermission
{

    /**
     * Handle an incoming request.model
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $pathInfo = $request->segment(1);
        $nonAuth = $request->getPathInfo();

        if ($pathInfo !== 'seller' && $pathInfo !== 'admin') {
            $response = $next($request);
            return $response;
        }

        $user = Auth::user();

        if (!$user || ($pathInfo === 'seller' && auth()->user()->hasRole('Admin')) || ($pathInfo === 'admin' && auth()->user()->hasRole('Seller')) ) {
            abort(405, 'Not Authorised.');
        }

        $response = $next($request);
        return $response;
    }
}
