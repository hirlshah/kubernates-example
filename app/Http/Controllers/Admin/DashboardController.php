<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Support\Facades\DB;
use MetaTag;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:admin-dashboard', ['only' => ['index']]);
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Dashboard'));
        MetaTag::set('description', config('app.rankup.company_title').' Dashboard Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $activeUsers = UserPlan::select('user_id')
            ->where('plan_id', '!=', Plan::FREE_PLAN_ID)
            ->where('status', 'active')
            ->distinct('user_id')
            ->count('user_id');

        $canceledUsers = UserPlan::where('plan_id', '!=', Plan::FREE_PLAN_ID)
            ->where('status', 'expired')
            ->count();

        $todayDate = getCarbonTodayEndDateTimeForUser();

        $weeklyUsers = User::select('id')
            ->where('last_login', '>=', $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s'))
            ->where('last_login', '<', $todayDate->clone()->format('Y-m-d H:i:s'))
            ->count('id');

        $planCountsData = UserPlan::join('plans', 'plans.id', '=', 'user_plans.plan_id')
            ->where('user_plans.status', 'active')
            ->select('plans.slug', 'plans.id', DB::raw('count(plan_id) as count'))
            ->groupBy('plans.id','plans.slug')
            ->orderBy('id')
            ->get();

        $planCounts = [];
        foreach ($planCountsData as $plan) {
            switch ($plan->slug) {
                case 'free':
                    $proYearPlan = $planCountsData->where('slug', 'pro_year')->first();
                    $planCounts[] = ['name' => __('free_plan_name'), 'count' => $proYearPlan ? $proYearPlan->count : 0];
                    break;
                case 'standard':
                    $standardPlan = $planCountsData->where('slug', 'standard')->first();
                    $planCounts[] = ['name' => __('standard_plan_name'), 'count' => $standardPlan ? $standardPlan->count : 0];
                    break;
                case 'pro_month':
                    $proMonthPlan = $planCountsData->where('slug', 'pro_month')->first();
                    $planCounts[] = ['name' => __('standard_month_plan_name'), 'count' => $proMonthPlan ? $proMonthPlan->count : 0];
                    break;
                case 'pro_year':
                    $proYearPlan = $planCountsData->where('slug', 'pro_year')->first();
                    $planCounts[] = ['name' => __('pro_year_plan_name'), 'count' => $proYearPlan ? $proYearPlan->count : 0];
                    break;
                case 'pro_year_199':
                    $proYear199Plan = $planCountsData->where('slug', 'pro_year_199')->first();
                    $planCounts[] = ['name' => __('pro_year_plan_name_199'), 'count' => $proYear199Plan ? $proYear199Plan->count : 0];
                    break;
                case 'pro_bi_year':
                    $proBiYearPlan = $planCountsData->where('slug', 'pro_bi_year')->first();
                    $planCounts[] = ['name' => __('pro_bi_year_plan_name'), 'count' => $proBiYearPlan ? $proBiYearPlan->count : 0];
                    break;
                case 'default':
                    $proYearPlan = $planCountsData->where('slug', 'pro_year')->first();
                    $planCounts[] = ['name' => $proYearPlan ? $proYearPlan->slug : '', 'count' => $proYearPlan ? $proYearPlan->count : 0];
                    break;
            }
        }
        return view('admin.dashboard', compact('activeUsers', 'canceledUsers', 'weeklyUsers', 'planCounts'));
    }
}