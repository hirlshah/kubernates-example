<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeCard extends Model
{
    use HasFactory;

    protected $table = 'stripe_cards';

    protected $guarded = ['id'];

    /**
	 * User
	 */
	public function user(){
		return $this->belongsTo(User::class);
	}
}
