<?php

namespace Techaxion\UserAccess;

use Illuminate\Support\ServiceProvider;

class UserAccessServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Automatically run migrations when the package is installed
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/database/migrations' => database_path('migrations'),
            ], 'migrations');
        }
    }
    
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Load the routes from the routes directory
        $this->loadRoutesFrom(__DIR__.'/routes/DynamicRoutes.php');
    }
}
