<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrelloTaskComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trello_task_comments';

    protected $guarded = ['id'];

    /**
     * Replies
     */
    public function replies() {
        return $this->hasMany(TrelloTaskComment::class, 'parent_id', 'id');
    }

    /**
     * User
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Attachments
     */
    public function attachments() {
        return $this->hasMany(TrelloTaskCommentAttachment::class, 'trello_task_comment_id', 'id');
    }

    /**
     * Trello task
     */
    public function trelloTask() {
        return $this->belongsTo(TrelloTask::class);
    }
}
