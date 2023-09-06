<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrelloBoard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trello_boards';

    protected $guarded = ['id'];

    /**
     * Trello statuses
     */
    public function trelloStatuses() {
        return $this->hasMany(TrelloStatus::class);
    }

    /**
     * Trello tasks
     */
    public function trelloTasks(){
        return $this->hasMany(TrelloTask::class);
    }

    /**
     * Users
     */
    public function users() {
        return $this->belongsToMany(User::class, 'user_trello_boards')->withTimestamps();
    }

    /**
     * Categories
     */
    public function categories() {
        return $this->hasMany(TrelloBoardCategory::class);
    }
}
