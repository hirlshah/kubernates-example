<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestApiAuthentication
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
        // Check if the token exists in the request or session, depending on where you store it
        $token = $request->header('Authorization');

        if(!$token) {
            return response()->json(['message' => __('Unauthorized')], 401);
        }

        // Validate the token (e.g., verify it against your SSO provider)
        // You can replace this with your SSO validation logic
        $validToken = $this->validateToken($token);

        if(!$validToken) {
            return response()->json(['message' => __('Unauthorized')], 401);
        }

        // Token is valid, continue with the request
        return $next($request);
    }

    // Replace this with your SSO validation logic
    private function validateToken($token)
    {
        if($token == env('REST_API_AUTH_TOKEN')) {
            return true;
        }
    }
}
