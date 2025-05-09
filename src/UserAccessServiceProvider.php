<?php

namespace Techaxion\UserAccess;

use Techaxion\UserAccess\Controllers;
use Illuminate\Support\ServiceProvider;
use Techaxion\UserAccess\Commands\InstallPackageCommand;
use Techaxion\UserAccess\Commands\RemovePackageCommand;
use Illuminate\Support\Facades\Artisan;

class UserAccessServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Or for your custom command
        Artisan::call('useraccess:install');
        
        // load routes
        $this->loadRoutesFrom(__DIR__.'/routes/DynamicRoutes.php');
    }
    
    public function register()
    {
        // Load migrations directly from the package (no need to publish)
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // load controllers
        $this->app->make(Controllers\RoleController::class);
        $this->app->make(Controllers\RightController::class);

        // load middleware
        $this->app['router']->aliasMiddleware('admin', \Techaxion\UserAccess\Middleware\AdminGuardMiddleware::class);

        // Register any application services if needed
        // For example, you can bind interfaces to implementations here
        $this->commands([ InstallPackageCommand::class, RemovePackageCommand::class ]);
    }

}
