<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeProduct extends Model
{
    use HasFactory;

    protected $table = 'stripe_products';
    
    protected $guarded = ['id'];
}
