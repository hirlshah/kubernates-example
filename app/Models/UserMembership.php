<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    use HasFactory;

    protected $table = 'user_memberships';

    protected $guarded = ['id'];

    /**
     * User 
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
