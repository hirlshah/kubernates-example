<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrelloTask extends Model
{
	use HasFactory, SoftDeletes;

	protected $table = 'trello_tasks';

	protected $guarded = ['id'];

	/**
     * Categories
     */
    public function categories() {
        return $this->belongsToMany(TrelloBoardCategory::class, 'trello_task_categories')->withTimestamps();
    }

    /**
     * Comments
     */
    public function comments() {
        return $this->hasMany(TrelloTaskComment::class);
    }

    /**
     * Users
     */
    public function users() {
        return $this->belongsToMany(User::class, 'trello_task_users')->withTimestamps();
    }

    /**
     * Attachments
     */
    public function attachments() {
        return $this->hasMany(TrelloTaskAttachment::class);
    }

    /**
     * First Attachment
     */
    public function firstImageAttachment() {
        return $this->hasOne(TrelloTaskAttachment::class)->where('type', 'image')->orderBy('created_at', 'asc');
    }
}
