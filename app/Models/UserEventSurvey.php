<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEventSurvey extends Model
{
    use HasFactory;

    protected $table = 'user_event_survey';
    
    protected $guarded = ['id'];

    /**
     * Event
     */
    public function event() {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }
}
