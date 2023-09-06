<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPerformanceRadialSetting extends Model
{
	use HasFactory, SoftDeletes;

	protected $table = 'user_performance_radial_settings';

	protected $guarded = ['id'];

	const DISTRIBUTOR_SLICE = 3;
	const CLIENT_SLICE = 2;

	/**
     * User 
     */
	public function user(){
		return $this->belongsTo(User::class);
	}
}
