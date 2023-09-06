<?php

namespace App\Models;

use App\Classes\Helper\StripeConnect;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\Stripe;

class PlanSetting extends Model
{
    use HasFactory;

    protected $table = 'plan_settings';

    protected $guarded = ['id'];

    /**
     * Get settings
     */
    public static function getSettings() {
        return self::query()->pluck('value', 'name')->toArray();
    }

    /**
     * Get free downline count
     */
    public static function getFreeDownlineCount() {
        $planSettings = PlanSetting::where('name', 'FREE_DOWNLINE')->first();
        return $planSettings?$planSettings->value:0;
    }
}
