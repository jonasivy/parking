<?php

namespace App\Trait;

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
        static::created(function () {
            Cache::tags(constant(self::class . '::CACHE_TAG'))->flush();
        });

        /**
         * @return void
         */
        static::updated(function () {
            Cache::tags(constant(self::class . '::CACHE_TAG'))->flush();
        });

        /**
         * @return void
         */
        static::deleted(function () {
            Cache::tags(constant(self::class . '::CACHE_TAG'))->flush();
        });
    }
}
