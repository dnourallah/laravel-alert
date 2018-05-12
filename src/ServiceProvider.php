<?php

namespace DNourallah\LaravelAlert;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerHelpers();

        $this->loadViewsFrom(__DIR__ . '/Views', 'laravelalert');

        $this->publishesFiles();

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'DNourallah\LaravelAlert\Storage\SessionStore',
            'DNourallah\LaravelAlert\Storage\AlertSessionStore'
        );
        $this->app->singleton('alert', function ($app) {
            return $this->app->make('DNourallah\LaravelAlert\Alert');
        });
    }

    /**
     * Register helpers file
     */
    public function registerHelpers()
    {
        // Load the helpers in src/functions.php
        if (file_exists($file = __DIR__ . '/functions.php')) {
            require $file;
        }
    }

    public function publishesFiles()
    {
        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/dnourallah'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../resources/assets' => resource_path('vendor/assets'),
        ], 'assets');
    }
}