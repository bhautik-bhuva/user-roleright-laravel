<?php

namespace Techaxion\UserAccess\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use File;

class InstallPackageCommand extends Command
{
    protected $signature = 'useraccess:install';
    protected $description = 'Install the UserAccess tables';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Add uninstall script to composer.json
        $composerPath = base_path('composer.json');
        $composerJson = json_decode(file_get_contents($composerPath), true);
        if(!in_array("remove-useraccess", $composerJson['scripts'])){
            $composerJson['scripts']['remove-useraccess'] = [
                "@php artisan useraccess:remove",
                "composer remove techaxion/user-roleright-laravel"
            ];
            file_put_contents($composerPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('✅ Added remove-useraccess script to composer.json');
        }

        if (file_exists($composerPath)) {
            if(!in_array("update-useraccess", $composerJson['scripts'])){
                $composerJson['scripts']['update-useraccess'] = [
                    "@php artisan useraccess:update"
                ];
                file_put_contents($composerPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                $this->info('✅ Added update-useraccess script to composer.json');
            }
        }

        // Check if the package is already installed
        $checkTable = $this->laravel['db']->getSchemaBuilder()->hasTable('module_action');
        if ($checkTable) {
            $this->error('Package is already installed.');
            return;
        }
        $this->info('🔧 Running UserAccess package installation...');

        $this->call('vendor:publish', ['--tag' => "useraccess-routes"]);

        // Run migrations
        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/Database/Migrations', '--force' => true]);
        $this->info('✅ Database Migration Done.');

        include_once dirname(__DIR__,1).'/Database/Seeders/ModuleActionDataSeeder.php';
        $this->call('db:seed', ['--class' => 'Techaxion\\UserAccess\\Database\\Seeders\\ModuleActionDataSeeder', '--force' => true]);
        $this->info('✅ Data Seeding Done.');

        // Other tasks (e.g., seeding, publishing configs)
        $this->call('vendor:publish', ['--provider' => "Techaxion\\UserAccess\\UserAccessServiceProvider"]);

        // Add route to web.php
        $webPath = base_path('routes/web.php');
        if (File::exists($webPath) && strpos(File::get($webPath), 'UserAccessDynamicRoutes.php') === false) {
            // File::append($webPath, "\n// UserAccess Dynamic Routes\nrequire __DIR__.'/UserAccessDynamicRoutes.php';\n");
            File::append($webPath, "\n// UserAccess Dynamic Routes\nrequire base_path('vendor/techaxion/user-roleright-laravel/src/routes/DynamicRoutes.php');\n");
            $this->info('✅ Route added to routes/web.php');
        } else {
            $this->warn('⚠️ routes/web.php already contains the dynamic route or could not be modified.');
        }

        // Publish assets
        $this->call('vendor:publish', ['--tag' => 'useraccess-assets']);
        $this->info('✅ Assets published to public/assets/vendor/useraccess');

        $this->info('🎉 Package installation complete!');
    }
}
