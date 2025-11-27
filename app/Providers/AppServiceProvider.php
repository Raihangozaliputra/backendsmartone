<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AttendanceService;
use App\Services\ReportService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AttendanceService::class, function ($app) {
            return new AttendanceService();
        });

        $this->app->singleton(ReportService::class, function ($app) {
            return new ReportService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}