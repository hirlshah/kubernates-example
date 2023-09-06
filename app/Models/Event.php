<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Event extends Authenticatable 
{
	use HasApiTokens, HasFactory, Notifiable, HasRoles, HasSlug, SoftDeletes;

	protected $table = 'events';

	protected $guarded = ['id'];

	public static function label() {
		return 'name';
	}

	/**
     * Event tags
     */
    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Event reps
     */
    public function reps() {
        return $this->belongsToMany(User::class, 'event_reps', 'event_id', 'member_id')->withPivot( 'status', 'created_at', 'updated_at');
    }

    /**
     * Event videos
     */
    public function videos() {
        return $this->belongsToMany(Video::class, 'event_video')->withTimestamps();
    }

    /**
     * Event documents
     */
    public function documents() {
        return $this->belongsToMany(Document::class, 'event_document');
    }

    /**
     * Event user
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Event contacts
     */
    public function contacts() {
        return $this->hasMany(Contact::class);
    }

    /**
     * Event rep for auth user
     */
    public function eventRepForCurrentUser() {
        $userId = Auth::id();

        return $this->reps()
            ->wherePivot('member_id', $userId)
            ->first();
    }

    /**
     * Event presentator
     */
    public function presentator() {
        return $this->belongsTo(User::class, 'presentator_id');
    }
}
