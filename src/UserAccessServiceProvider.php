<?php

namespace Techaxion\UserAccess;
use Techaxion\UserAccess\Controllers;
use Illuminate\Support\ServiceProvider;
use Techaxion\UserAccess\Commands\InitPackageConfigCommand;
use Techaxion\UserAccess\Commands\InstallPackageCommand;
use Techaxion\UserAccess\Commands\RemovePackageCommand;
use Illuminate\Support\Facades\Artisan;
use Techaxion\UserAccess\Commands\UpdatePackageCommand;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class UserAccessServiceProvider extends ServiceProvider
{
    public function boot()
    {

        // This runs on every request
        $sessionname = config('session.cookie');

        $rawCookie = Request::cookie($sessionname);
        if ($rawCookie) {
            $decrypted = Crypt::decrypt($rawCookie, false);
            $session = explode('|', $decrypted)[1];
            $session = DB::table('sessions')->select('user_id')->where('id', $session)->first();
            session::put('admin_user_id' , $session->user_id);
            view()->share('admin_user_id', $session->user_id);
        }
    }

    public function register()
    {
        // Load migrations directly from the package (no need to publish)
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // $this->publishes([
        //     __DIR__.'/routes/DynamicRoutes.php' => base_path('routes/UserAccessDynamicRoutes.php'),
        // ], 'useraccess-routes');

        // Load assets
        $this->publishes([
            __DIR__.'/assets/hierarchical' => public_path('assets/vendor/useraccess/hierarchical'),
        ], 'useraccess-assets');

        $pluginPath = dirname(__FILE__). '/configuration.json';
        if(file_exists($pluginPath)){
            $content = file_get_contents($pluginPath);
            $content = json_decode($content, true);
            $layoutPath = $content['layout_path'];
            $frontend = $content['frontend'];
            $layoutFile = basename($layoutPath, '.blade.php');
            $this->loadViewsFrom(dirname($layoutPath), 'laravelMain');
            view()->share('layout_file', $layoutFile);
        
            // Define constant to access useraccess config anywhere (controllers, models, etc.)
            if (!defined('USERACCESS_CONTENT')) {
                define('USERACCESS_CONTENT', $content);
            }
        }
        // Load views from the package
        $this->loadViewsFrom(__DIR__.'/views', 'useraccess');

        // Register any application services if needed
        // For example, you can bind interfaces to implementations here
        $this->commands([ InitPackageConfigCommand::class, InstallPackageCommand::class, RemovePackageCommand::class, UpdatePackageCommand::class ]);
    }

}
