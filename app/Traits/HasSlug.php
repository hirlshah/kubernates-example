<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug as BaseHasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Wrap base HasSlug and provide:
 * default SlugOptions
 * and findBySlug methods.
 */
trait HasSlug
{
    use BaseHasSlug;

    /**
     * Get Options for generating slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(static::label())
            ->slugsShouldBeNoLongerThan(50)
            ->saveSlugsTo('slug');
    }

    /**
     * Retrieve model by slug or return null.
     *
     * @param string $slug slug to lookup
     * @param bool $withTrashed
     *
     * @return Illuminate\Database\Eloquent\Model or null
     */
    public static function findBySlug(string $slug, bool $withTrashed = false): ?Model
    {
        return static::when($withTrashed, function ($query) {
            return $query->withTrashed();
        })
            ->where('slug', $slug)->first();
    }

    /**
     * Retrieve model by slug. 404 if not found.
     *
     * @param string $slug slug to lookup
     * @param bool $withTrashed
     */
    public static function findBySlugOrFail(string $slug, bool $withTrashed = false): Model
    {
        if ($model = static::findBySlug($slug, $withTrashed)) {
            return $model;
        }

        abort(404);
    }
}
