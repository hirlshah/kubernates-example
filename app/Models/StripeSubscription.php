<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeSubscription extends Model
{
    use HasFactory;

    protected $table = 'stripe_subscriptions';
    
    protected $guarded = ['id'];

    /**
     * Create subscription
     */
    public static function createSubscription($customerId, $stripeSubscription) {
        $data = [
            'user_id' => $customerId,
            'plan_id' => $stripeSubscription->plan_id,
            'admin_coupon_id' => isset($stripeSubscription['admin_coupon_id'])? $stripeSubscription->admin_coupon_id : NULL,
            'stripe_id' => $stripeSubscription->id,
            'price' => $stripeSubscription->plan->id,
            'product' => $stripeSubscription->plan->product,
            'item' => $stripeSubscription->items->data[0]->id,
            'quantity' => $stripeSubscription->quantity,
            'default_payment_method' => $stripeSubscription->default_payment_method,
            'unit_amount_decimal' => $stripeSubscription->plan->amount,
            'total_amount_decimal' => $stripeSubscription->plan->amount * $stripeSubscription->quantity,
            'currency' => $stripeSubscription->plan->currency,
            'interval' => $stripeSubscription->plan->interval,
            'period_start' => Carbon::createFromTimestamp($stripeSubscription->current_period_start)->format('Y-m-d H:i:s'),
            'period_end' => Carbon::createFromTimestamp($stripeSubscription->current_period_end)->format('Y-m-d H:i:s'),
            'stripe_object' => json_encode($stripeSubscription),
            'status' => $stripeSubscription->status,
            'coupon_code' => $stripeSubscription['promo'] ?? NULL
        ];
        if($stripeSubscription->cancel_at){
            $data['canceled_at'] = Carbon::createFromTimestamp($stripeSubscription->cancel_at)->format('Y-m-d H:i:s');
        }

        return self::create($data);
    }

    /**
     * Update subscription
     */
    public static function updateSubscription($subscription, $stripeSubscription) {
        $data = [
            'plan_id' => $stripeSubscription->plan_id,
            'price' => $stripeSubscription->plan->id,
            'product' => $stripeSubscription->plan->product,
            'item' => $stripeSubscription->items->data[0]->id,
            'quantity' => $stripeSubscription->quantity,
            'default_payment_method' => $stripeSubscription->default_payment_method,
            'unit_amount_decimal' => $stripeSubscription->plan->amount,
            'total_amount_decimal' => $stripeSubscription->plan->amount * $stripeSubscription->quantity,
            'currency' => $stripeSubscription->plan->currency,
            'interval' => $stripeSubscription->plan->interval,
            'period_start' => Carbon::createFromTimestamp($stripeSubscription->current_period_start)->format('Y-m-d H:i:s'),
            'period_end' => Carbon::createFromTimestamp($stripeSubscription->current_period_end)->format('Y-m-d H:i:s'),
            'stripe_object' => json_encode($stripeSubscription),
            'status' => $stripeSubscription->status,
            'coupon_code' => $stripeSubscription['promo'] ?? NULL
        ];
        if($stripeSubscription->cancel_at){
            $data['canceled_at'] = Carbon::createFromTimestamp($stripeSubscription->cancel_at)->format('Y-m-d H:i:s');
        }
        $subscription->update($data);
        return $subscription;
    }
}
