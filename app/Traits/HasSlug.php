<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (!$model->shouldGenerateSlug()) {
                return;
            }
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->{$model->getSlugSource()});
            }
        });

        static::updating(function ($model) {
            if (!$model->shouldGenerateSlug()) {
                return;
            }
            if ($model->isDirty($model->getSlugSource())) {
                $model->slug = $model->generateUniqueSlug($model->{$model->getSlugSource()});
            }
        });
    }

    protected function shouldGenerateSlug()
    {
        if (property_exists($this, 'hasSlug') && $this->hasSlug === false) {
            return false;
        }
        
        if (!isset($this->{$this->getSlugSource()})) {
            return false;
        }

        return true;
    }

    protected function getSlugSource()
    {
        return 'name';
    }

    protected function generateUniqueSlug($value)
    {
        if (!$this->shouldGenerateSlug()) {
            return null;
        }

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
        if (!$this->shouldGenerateSlug()) {
            return false;
        }

        $query = static::where('slug', $slug);

        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        return $query->exists();
    }

    public function getRouteKeyName()
    {
        if (!$this->shouldGenerateSlug()) {
            return $this->getKeyName();
        }
        return 'slug';
    }
}
