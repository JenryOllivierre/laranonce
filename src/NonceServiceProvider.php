<?php

namespace Laranonce;

use Illuminate\Support\ServiceProvider;
use Laranonce\Commands\PruneNoncesCommand;

class NonceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        if (Config::driver() == 'database') {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        $this->publishes([
            __DIR__ . '/../config/nonce.php' => config_path('nonce.php'),
        ], 'laranonce-config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            PruneNoncesCommand::class,
        ]);
    }
}
