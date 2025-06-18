<?php

namespace nextdev\nextdashboard\Providers;

use Illuminate\Support\ServiceProvider;
use nextdev\nextdashboard\MediaLibrary\PathGenerators\TicketPathGenerator;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class PackageServiceProvider extends ServiceProvider
{
    public function boot()
    {

        // app()->bind(PathGenerator::class, TicketPathGenerator::class);

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/dashboard.php');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('config.php'),
        ]);

        // Publish migrations: php artisan vendor:publish --tag=nextdashboard-migrations
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'nextdashboard-migrations');

        // Publish seeders: php artisan vendor:publish --tag=nextdashboard-seeders
        $this->publishes([
            __DIR__.'/../../database/seeders' => database_path('seeders'),
        ], 'nextdashboard-seeders');
    }

    public function register()
    {
        // Merge package config
        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravelusermanager');
    }
}