<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->{$model->getSlugSource()});
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->getSlugSource())) {
                $model->slug = $model->generateUniqueSlug($model->{$model->getSlugSource()});
            }
        });
    }

    protected function getSlugSource()
    {
        return 'name';
    }

    protected function generateUniqueSlug($value)
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $count = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    protected function slugExists($slug)
    {
        $query = static::where('slug', $slug);

        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        return $query->exists();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
