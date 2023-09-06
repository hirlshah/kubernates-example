<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoVisiterLog extends Model
{
    use HasFactory;

    protected $table = 'video_visiter_logs';

    protected $guarded = ['id'];
}
