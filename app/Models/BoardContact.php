<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardContact extends Model
{
    use HasFactory,SoftDeletes;

	protected $table = 'board_contact';

	protected $guarded = ['id'];

	/**
	 * Board
	 */
	public function board(){
		return $this->belongsTo(Board::class);
	}
}
