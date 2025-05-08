<?php

namespace Techaxion\UserAccess;

use Illuminate\Support\ServiceProvider;

class UserAccessServiceProvider extends ServiceProvider
{
    public function boot()
    {  
        // $this->loadMigrationsFrom(dirname(__DIR__,1).'/database/migrations');

        // $this->publishes([
        //     dirname(__DIR__,1).'/config/access.php' => config_path('access.php'),
        // ]);
    }

    public function register()
    {
        $this->loadRoutesFrom(dirname(__DIR__,1).'/routes/DynamicRoutes.php');
        
        // bindings or helpers
        // $this->mergeConfigFrom(dirname(__DIR__,1).'/config/access.php', 'access');
    }
}
