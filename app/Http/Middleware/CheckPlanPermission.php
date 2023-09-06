<?php

namespace App\Http\Middleware;

use App\Models\UserPlan;
use App\Classes\Helper\Ibuumerang;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Cache;

class CheckPlanPermission
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

        $routeName = $request->route()->getName();

        if(config('app.rankup.comapny_name') == 'ibuumerang_rankup') {

            $myPlan = [];
            app('view')->composer('layouts.seller.index', function ($view) use ($myPlan) {
                $planSlug = 'none';
                $proBadge = '';
                $view->with(['seller_current_plan' => $planSlug, 'global_pro_badge' => $proBadge]);
            });
            
            $isPlanActive = Cache::has('ibuumerang_rankup_check_plan_'.Auth::id());
            if(!$isPlanActive) {
                if(Auth::user()->email == 'ibuumerang@dev.com' || Auth::user()->email == 'seller@ibuumerang.com') {
                    $isPlanActive = true;
                } else { 
                    $isPlanActive = Ibuumerang::checkSubscription(); 
                }
            }
            
            if($isPlanActive == true) {
                 Cache::set('ibuumerang_rankup_check_plan_'.Auth::id(), true, 86400); 
                return $next($request);
            } else {
                Session::flush();
                return Redirect::to('https://myibuumerang.com/signin');
            }
        }

        //first check if there's any card added if not then redirect to plan page so that user adds the card
        if (!in_array($routeName, ['seller.setting.my-subscription', 'seller.add-card', 'seller.card.activate', 'seller.plan.update', 'seller.coupon.validate']) && Auth::user()->stripeCards()->count() === 0) {
            return redirect()->route('seller.setting.my-subscription', ['#add_card_div']);
        }

        $myPlan = UserPlan::getMyplan();
        $isPlanActive = false;
        if(!empty($myPlan)) {
            $isPlanActive = UserPlan::isPlanActive($myPlan);
        }
        
        app('view')->composer('layouts.seller.index', function ($view) use ($myPlan, $isPlanActive) {
            $planSlug = $myPlan && $isPlanActive && $myPlan->plan ? $myPlan->plan->slug : 'none';
            $proBadge = $planSlug === 'standard' ? '<span class="badge">PRO</span>' : '';
            $view->with(['seller_current_plan' => $planSlug, 'global_pro_badge' => $proBadge]);
        });

        if (in_array($routeName, ['seller.plan.update', 'seller.setting.my-subscription', 'seller.add-card', 'seller.card.activate', 'seller.coupon.validate'])) {
            return $next($request);
        }

        if (!$isPlanActive) {
            Session::flash('success_top', __('Your do not have any active plan.'));
            return redirect(route('seller.setting.my-subscription'));
        }
        if (!empty($myPlan->plan) && $isPlanActive && in_array($myPlan->plan->slug, ['free', 'pro_month', 'pro_year', 'pro_year_199', 'pro_bi_year'])) {
            return $next($request);
        }

        $restrictedRoutes = [
            'seller.members',
            'analytics',
            'seller-leaderboard',
        ];
        if (in_array($routeName, $restrictedRoutes)) {
            Session::flash('success_top', __('Your current Plan do not have access to requested page. Please upgrade to gain access.'));
            return redirect(route('seller.setting.my-subscription'));
        }

        return $next($request);

    }
}
