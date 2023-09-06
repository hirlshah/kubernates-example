<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoVisiter extends Model
{
    use HasFactory;

    protected $table = 'video_visiters';

    protected $guarded = ['id'];

    /**
     * User
     */
    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * Prospection video
     */
    public function video() {
        return $this->belongsTo(ProspectionVideo::class,'prospection_video_id','id');
    }

    /**
     * Contact
     */
    public function contact() {
        return $this->belongsTo(Contact::class,'contact_id','id');
    }
}
