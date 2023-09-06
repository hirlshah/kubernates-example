<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Labelled extends Model
{
	use HasFactory,Notifiable;

	protected $table = 'labelleds';

	protected $guarded = ['id'];

	/**
	 * Get the parent model (task, contact).
	 */
	public function labelled()
	{
		return $this->morphTo();
	}

}
