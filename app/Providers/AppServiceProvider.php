<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

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
    public function boot() {
        // Macro FootballData
        Http::macro('footballData', function () {
            return Http::withHeaders([
                'X-Auth-Token' => env('FootballData_TOKEN'),
            ])->baseUrl(env('FootballData_DOMAIN'));
        });
    }
}
