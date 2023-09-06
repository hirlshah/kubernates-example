<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory,SoftDeletes;

	protected $table = 'contacts';

	protected $guarded = ['id'];

	/**
	 * Boards
	 */
	public function boards() {
		return $this->belongsToMany(Board::class)->withPivot('status');
	}

	/**
	 * Follow up
	 */
	public function followUp() {
		return $this->hasOne(FollowUp::class);
	}

	/**
	 * User
	 */
	public function User() {
		return $this->hasOne(User::class,'id','user_id');
	}

	/**
	 * Event
	 */
	public function event() {
		return $this->hasOne(Event::class,'id','event_id');
	}

	/**
	 * Survey
	 */
	public function survey() {
		return $this->hasMany(UserEventSurvey::class,'contact_id','id');
	}

	/**
	 * Log
	 */
	public function log() {
		return $this->hasMany(ContactLog::class,'contact_id','id');
	}

	/**
	 * Conatct events
	 */
	public function contactEvent() {
		return $this->hasMany(ContactEvents::class,'contact_id','id');
	}

	/**
	 * Board contacts
	 */
	public function contactBoard() {
		return $this->hasMany(BoardContact::class,'contact_id','id');
	}

	/**
	 * Get the contact label.
	 */
	public function labels() {
		return $this->morphToMany(Label::class, 'labelled');
	}

	/**
	 * Video visiters
	 */
	public function videoVisiters() {
		return $this->hasMany(VideoVisiter::class,'contact_id','id');
	}
}
