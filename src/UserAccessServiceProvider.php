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
    }
    
    public function register()
    {
        // Load migrations directly from the package (no need to publish)
        $this->loadMigrationsFrom(__DIR__.'/database/migrations'); 
        
        // load routes
        $this->publishes([
            __DIR__.'/routes/DynamicRoutes.php' => base_path('routes/UserAccessDynamicRoutes.php'),
        ], 'useraccess-routes');

        $this->loadViewsFrom(base_path().'/resources/views/admin/layouts', 'laravelMain');

        // Load views from the package
        $this->loadViewsFrom(__DIR__.'/views', 'useraccess');

        // Register any application services if needed
        // For example, you can bind interfaces to implementations here
        $this->commands([ InstallPackageCommand::class, RemovePackageCommand::class ]);
    }

}
