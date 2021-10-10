<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EmailService;
use App\Services\ActivityLogService;
use App\Services\FileService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('FileService', function($app){
            return new FileService();
        });

        $this->app->singleton('ContentService', function($app){
            return new ContentService();
        });

        $this->app->singleton('EmailService', function($app){
            return new EmailService();
        });

        $this->app->singleton('ActivityLogService', function($app){
            return new ActivityLogService();
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
