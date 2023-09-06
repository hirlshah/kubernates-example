<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourLogs extends Model
{
    use HasFactory;

    protected $table = 'tour_logs';

	protected $guarded = ['id'];
}
