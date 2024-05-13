<?php

namespace HocVT\LaravelOpcache;

use Illuminate\Support\ServiceProvider;

class OpcacheServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\OpcacheControlCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/opcache.php' => config_path('opcache.php'),
            ], 'config');
        }
    }

    public function register()
    {
        // config
        $this->mergeConfigFrom(__DIR__.'/../config/opcache.php', 'opcache');

        // bind routes
        $this->app->router->group([], function ($router) {
            require __DIR__.'/../routes/opcache.php';
        });
    }
}
