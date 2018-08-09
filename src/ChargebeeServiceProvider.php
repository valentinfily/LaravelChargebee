<?php

namespace ValentinFily\LaravelChargebee;

use Illuminate\Support\ServiceProvider;
use ValentinFily\LaravelChargebee\Commands\Install;

class ChargebeeServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Copies the config file to project config directory on: php artisan vendor:publish
        $this->publishes([
            __DIR__.'/config/chargebee.php' => config_path('chargebee.php'),
        ], 'config');

        // Publishes the migrations into the application's migrations folder
        $this->publishes([
            __DIR__.'/Migrations/' => database_path('migrations'),
        ], 'migrations');

        if (! $this->app->routesAreCached() && config('chargebee.publish_routes', false)) {
            require __DIR__.'/routes.php';
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Install::class
        ]);
    }
}
