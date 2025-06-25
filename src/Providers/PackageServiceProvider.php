<?php

namespace nextdev\nextdashboard\Providers;

use Illuminate\Support\ServiceProvider;
use nextdev\nextdashboard\Console\ListEventsCommand;
use nextdev\nextdashboard\Models\Admin;
use nextdev\nextdashboard\Models\Ticket;
use nextdev\nextdashboard\Observers\AdminObserver;
use nextdev\nextdashboard\Observers\TicketObserver;
use Illuminate\Support\Facades\Config;
use nextdev\nextdashboard\Console\DeleteExpiredOtps;

class PackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Admin::observe(AdminObserver::class);
        Ticket::observe(TicketObserver::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ListEventsCommand::class,
                DeleteExpiredOtps::class,
            ]);
        }

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/dashboard.php');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        
        // Load Views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'nextdashboard');

        // Load Translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'nextdashboard');

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
        // Register the event service provider
        $this->app->register(EventServiceProvider::class);

        Config::set('auth.defaults.guard', 'admin');
        
    }
}