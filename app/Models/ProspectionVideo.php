<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasSlug;
use Cohensive\Embed\Facades\Embed;

class ProspectionVideo extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $table = 'prospection_videos';

    protected $guarded = ['id'];

    public static function label() {
		return 'title';
	}

    /**
     * Category
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * User
     */
    public function user() {
        return $this->hasOne(User::class,'id','user_id');
    }
}
