<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
	use HasFactory;

	protected $table = 'boards';

	protected $guarded = ['id'];

	/**
	 * User
	 */
	public function user() {
		return $this->belongsTo(User::class);
	}

	/**
	 * Contacts
	 */
	public function contacts() {
		return $this->belongsToMany(Contact::class)->withPivot('status')->orderBy('order', 'asc')->whereNull('board_contact.deleted_at');
	}

	/**
	 * Get board user without order.
	 */
	public function withOutOrderContacts() {
		return $this->belongsToMany(Contact::class)->withPivot('status')->whereNull('board_contact.deleted_at');
	}

	/**
	 * Board contacts
	 */
	public function boardContacts() {
		return $this->hasMany(BoardContact::class);
	}
}
