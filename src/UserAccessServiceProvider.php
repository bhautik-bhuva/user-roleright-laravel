<?php

namespace Techaxion\UserAccess;

use Illuminate\Support\ServiceProvider;

class UserAccessServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(dirname(__DIR__,1).'/database/migrations');
        $this->mergeConfigFrom(dirname(__DIR__,1).'/config/access.php', 'access');
        $this->publishes([
            dirname(__DIR__,1).'/config/access.php' => config_path('access.php'),
        ]);
    }

    public function register()
    {
        // bindings or helpers
    }
}
