<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventReps extends Model
{
    use HasFactory;

    protected $table = 'event_reps';

    protected $guarded = ['id'];

    /**
	 * User
	 */
	public function User(){
		return $this->hasOne(User::class, 'id', 'member_id');
	}
}
