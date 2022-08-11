<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    /**
     * Define default flushing of model cache.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * @return void
         */
        static::created(function ($model) {
            Cache::tags($model->getTable() . '-query')->flush();
        });

        /**
         * @return void
         */
        static::updated(function ($model) {
            Cache::tags($model->getTable() . '-query')->flush();
        });

        /**
         * @return void
         */
        static::deleted(function ($model) {
            Cache::tags($model->getTable() . '-query')->flush();
        });
    }
}
