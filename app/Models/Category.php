<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $guarded = ['id'];

	/**
	 * Get category by modal type.
	 *
	 * @param $modalType
	 *
	 * @return Collection
	 */
    public static function getOptions($modalType){
        $user = Auth::User();
        $memberIds = User::getDownlineIds($user->id);
        array_unshift($memberIds, $user->root_id);
        array_unshift($memberIds, $user->id);
        $categoryIds = DB::table('user_category')->whereRaw('user_id IN ('.getDownlinesStr(implode(',', array_filter($memberIds))).')')->pluck('category_id')->toArray();
        return Category::query()->whereIn('id', $categoryIds)->where('model_type', $modalType)->orderBy('id','asc')->pluck('name', 'id');
    }

    /**
     * Category Users
     */
    public function users() {
        return $this->belongsToMany(User::class,'user_category');
    }

    /**
     * Sub categories
     */
    public function subCategories() {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    /**
     * Sub category videos
     */
    public function subCategoryVideos() {
        return $this->hasMany(Video::class, 'sub_category_id', 'id');
    }

    /**
     * Category videos
     */
    public function categoryVideos() {
        return $this->hasMany(Video::class, 'category_id', 'id');
    }
}
