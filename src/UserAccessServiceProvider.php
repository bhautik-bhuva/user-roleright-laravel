<?php

namespace Techaxion\UserAccess;

use Illuminate\Support\ServiceProvider;

class UserAccessServiceProvider extends ServiceProvider
{
    public function boot()
    {  
    }
    
    public function register()
    {
        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ], 'migrations');
        
        // Load the routes from the routes directory
        $this->loadRoutesFrom(__DIR__.'/routes/DynamicRoutes.php');
   }
}
