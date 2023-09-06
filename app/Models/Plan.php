<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $guarded = ['id'];

    const FREE_PLAN_ID = 1;
    const TWO_YEAR_PLAN_ID = 6;
}
