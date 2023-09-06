<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrelloBoardCategory extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $table = 'trello_board_categories';

    protected $guarded = ['id'];

    public static function label() {
        return 'title';
    }

    /**
     * Trello board categories
     */
    public function categories() {
        return $this->belongsToMany(TrelloTask::class, 'trello_task_categories')->withTimestamps();
    }
}
