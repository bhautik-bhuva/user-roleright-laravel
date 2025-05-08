<?php

namespace Techaxion\UserAccess;

use Illuminate\Support\ServiceProvider;

class UserAccessServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load migrations directly from the package (no need to publish)
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Optional: Allow user to publish migrations manually if needed
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/database/migrations' => database_path('migrations'),
            ], 'migrations');
        }

        // Load the routes from the routes directory
        $this->loadRoutesFrom(__DIR__.'/routes/DynamicRoutes.php');
    }
    
    public function register()
    {
        // Register any application services if needed
        // For example, you can bind interfaces to implementations here
    }
}
