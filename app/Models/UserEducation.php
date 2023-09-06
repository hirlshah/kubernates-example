<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEducation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_educations';

    protected $guarded = ['id'];

    /**
     * User 
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
