<?php

namespace App\Http\Middleware;

use App\Models\PlanSetting;
use App\Models\User;
use App\Models\UserPlan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ViewMiddleware
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
        if(!Auth::guest() && Auth::user()->hasRole('Seller')){
            $userPlan = UserPlan::getMyplan();
            if(!empty($myPlan)) {
                $isPlanActive = UserPlan::isPlanActive($userPlan);
                View::share( 'userPlan', $userPlan );
                View::share( 'isUserPlanActive', $isPlanActive );
            } else {
                
                View::share( 'userPlan', null );
                View::share( 'isUserPlanActive', false );
            }
        }
        return $next($request);
    }
}
