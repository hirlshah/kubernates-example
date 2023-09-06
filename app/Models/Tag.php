<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Tag extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

	protected $table = 'tags';

	protected $guarded = ['id'];

    /**
     * Get tags
     */
    public static function getOptions() {
        return self::query()->orderBy('id','asc')->pluck('name', 'id');
    }

    /**
     * Update or create tags
     */
    public static function findOrCreate($tags) {
        $ids = [];
        foreach($tags as $tag){
            $tag = ucfirst(trim($tag));
            $find = Tag::query()->updateOrCreate(['name'=>$tag], []);
            $ids[] = $find->id;
        }
        return $ids;
    }
}
