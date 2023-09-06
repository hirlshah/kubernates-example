<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCoupon extends Model
{
    use HasFactory;

    protected $table = 'admin_coupons';

    protected $guarded = ['id'];

    /**
     * Find coupon
     */
    public static function findCoupon($coupon) {
        return AdminCoupon::query()->where(['code'=>$coupon, 'is_active'=>1])
            ->where(function($query){
                $query->where('valid_until', '>=', Carbon::now())
                    ->orWhereNull('valid_until');
            })
            ->first();
    }

    /**
     * Check coupon is already used or not
     */
    public function isCouponAlreadyUsed() {
        $alreadyUsed = StripeSubscription::query()->where(['admin_coupon_id'=>$this->id])->first();
        if($alreadyUsed){
            return true;
        }
        return FALSE;
    }
}
