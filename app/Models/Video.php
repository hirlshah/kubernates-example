<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cohensive\Embed\Facades\Embed;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'videos';

    protected $guarded = ['id'];

    /**
     * Tags
     */
    public function tags() {
        return $this->belongsToMany(Tag::class,'video_tag');
    }

    /**
     * User 
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Get video html 
     */
    public function getVideoHtmlAttribute()
    {
        $embed = Embed::make($this->video)->parseUrl();

        if (!$embed)
            return '';

        $embed->setAttribute(['width' => "100%", 'height' => 200]);
        return $embed->getHtml();
    }

    /**
     * Category
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // users sync with video completed
    public function videoCompleted()
    {
        return $this->belongsToMany(User::class, 'user_video')->withPivot('video_id');
    }



    /**
     * Sub category
     */
    public function subCategory() {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
}
