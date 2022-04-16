<?php

namespace App\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use App\FootballData\FootballData;

class FootballDataServiceProvider extends ServiceProvider {


    /**
     * Register services.
     *
     * @return void
     */

    public function register() {
        App::bind('FootballData', FootballData::class);
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */

    public function boot() {
        //
    }
}
