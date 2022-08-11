<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('DB_QUERY_LOG')) {
            DB::listen(function ($query) {
                Log::channel('database-query')
                    ->info($query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL);
            });
        }
    }
}
