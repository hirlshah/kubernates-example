<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrelloBoardLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trello_board_logs';

    protected $guarded = ['id'];
}
