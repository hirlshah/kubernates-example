<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contact_logs';

    protected $guarded = ['id'];

    /**
	 * Create log
	 */
    public static function createLog($contactId, $status_id, $user_id = '') {
        $now = getCarbonNowForUser();
        return self::query()->updateOrCreate(['user_id' => !empty($user_id) ? $user_id : Auth::id(), 'contact_id'=>$contactId, 'status'=>$status_id], ['date'=>$now->format('Y-m-d')]);
    }

    /**
	 * Delete log
	 */
	public static function deleteLog($contactId, $status_id, $user_id = '') {
        $now = getCarbonNowForUser();
        self::query()->where(['user_id' => !empty($user_id) ? $user_id : Auth::id(), 'contact_id'=>$contactId, 'status'=>$status_id])->delete();
    }

    /**
	 * Contact
	 */
	public function contact() {
		return $this->belongsTo(Contact::class);
	}

    /**
	 * User
	 */
	public function user() {
		return $this->belongsTo(User::class);
	}
}
