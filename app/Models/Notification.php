<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $guarded = ['id'];

    /**
     * Get user notifacation
     */
    public static function scopeIsUser($query){
        return $query->where('user_id',Auth::user()->id);
    }
}
