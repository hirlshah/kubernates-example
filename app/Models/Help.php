<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cohensive\Embed\Facades\Embed;

class Help extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'helps';

    protected $guarded = ['id'];

    /**
     * Get video html 
     */
    public function getVideoHtmlAttribute()
    {
        $embed = Embed::make($this->url)->parseUrl();

        if (!$embed)
            return '';

        $embed->setAttribute(['width' => "100%", 'height' => 200]);
        return $embed->getHtml();
    }
}
