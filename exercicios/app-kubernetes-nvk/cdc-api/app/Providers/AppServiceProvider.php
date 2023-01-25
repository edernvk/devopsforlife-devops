<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Faker\Generator::class, function () {
            return \Faker\Factory::create('pt_BR');
        });

        if ($this->app->environment(['local'])) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // DB::listen(function($sql) {
        //     Log::info($sql->sql);
        // });

        // use this to set global Carbon `now()` return to a specific date
        // $testDate = Carbon::createFromFormat('Y-m-d', '2021-09-14');
        // Carbon::setTestNow($testDate);
    }
}
