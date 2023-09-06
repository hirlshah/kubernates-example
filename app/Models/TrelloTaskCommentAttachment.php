<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrelloTaskCommentAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trello_task_comment_attachments';

    protected $guarded = ['id'];
}
