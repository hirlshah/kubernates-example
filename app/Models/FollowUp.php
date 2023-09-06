<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUp extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'follow_ups';

    protected $guarded = ['id'];

    /**
     * User
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Contact
     */
    public function contact() {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get day count
     */
    public function getDayCount() {
        $now = getCarbonNowForUser();
        return Carbon::parse(convertDateFormatWithTimezone($this->follow_up_date, 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM'))->diffInDays($now, false);
    }
}
