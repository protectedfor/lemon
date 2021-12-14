<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Date\Date;
use Schema;

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
        if (env('APP_DEBUG'))
            DB::enableQueryLog();

        Schema::defaultStringLength(125);

        Date::setlocale(config('app.locale'));
    }
}
