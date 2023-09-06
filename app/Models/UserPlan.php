<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserPlan extends Model
{
    use HasFactory;

    protected $table = 'user_plans';

    protected $guarded = ['id'];

    /**
     * Auth user plan 
     */
    public static function getMyplan() {
        return self::where(['user_id'=>Auth::id()])->first();
    }

    /**
     * User plan 
     */
    public static function getUserPlan($id) {
        return self::where(['user_id'=>$id])->first();
    }

    /**
     * Check plan active or not 
     */
    public static function isPlanActive($plan) {
        $now = Carbon::now()->timestamp;
        $planExpiry = Carbon::createFromFormat('Y-m-d H:i:s', $plan->expiration)->timestamp;
        return $plan->status === 'active' && $planExpiry > $now;
    }

    /**
     * StripeSubscription 
     */
    public function stripeSubscription() {
        return $this->belongsTo(StripeSubscription::class, 'stripe_subscription_id', 'id');
    }

    /**
     * Plan 
     */
    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    /**
     * User 
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
