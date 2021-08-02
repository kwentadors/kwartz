<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;


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
        // Remove wrapping of resources in data
        JsonResource::withoutWrapping();

        \DB::connection()->enableQueryLog();
        \DB::listen(function ($query) {
            // \Log::debug("DB: " . $query->sql . "[".  implode(",",$query->bindings). "]");
            \Log::info(
                $query->sql,
                $query->bindings,
                $query->time
            );
        });
    }
}
