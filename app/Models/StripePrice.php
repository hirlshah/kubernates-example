<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripePrice extends Model
{
    use HasFactory;

    protected $table = 'stripe_prices';
    
    protected $guarded = ['id'];
}
