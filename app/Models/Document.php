<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'documents';

    protected $guarded = ['id'];

    /**
     * Tags
     */
    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * User 
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Category
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
