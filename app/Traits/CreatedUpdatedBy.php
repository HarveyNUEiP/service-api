<?php

namespace App\Traits;

Trait CreatedUpdatedBy
{
    /**
     * Boot the trait
     */
    public static function bootCreatedUpdatedBy()
    {
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id ?? null;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id ?? null;
            }
        });

        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id ?? null;
            }
        });
    }
}