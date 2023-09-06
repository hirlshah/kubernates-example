<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrelloStatus extends Model
{
	use HasFactory, SoftDeletes;

	protected $table = 'trello_statuses';

	protected $guarded = ['id'];

	/**
	 * Trello task
	 */
	public function tasks(){
		return $this->hasMany(TrelloTask::class , 'trello_status_id');
	}
}
