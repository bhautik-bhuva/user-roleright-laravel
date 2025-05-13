<?php

namespace Techaxion\UserAccess\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
        $this->info('Running package installation...');
       
        // Check if the package is already installed
        if ($this->laravel['db']->getSchemaBuilder()->hasTable('module_action')) {
            $this->error('Package is already installed.');
            return;
        }
        $this->call('vendor:publish', ['--tag' => "useraccess-routes"]);

        // Run migrations
        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/database/migrations/create_module_action_table.php', '--force' => true]);
        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/database/migrations/create_role_action_table.php', '--force' => true]);
        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/database/migrations/create_right_action_table.php', '--force' => true]);
        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/database/migrations/create_roles_table.php', '--force' => true]);
        
        include_once __DIR__.'/../database/seeders/ModuleActionDataSeeder.php';
        $this->call('db:seed', ['--class' => 'Techaxion\\UserAccess\\Database\\Seeders\\ModuleActionDataSeeder', '--force' => true]);

        // Other tasks (e.g., seeding, publishing configs)
        $this->call('vendor:publish', ['--provider' => "Techaxion\\UserAccess\\UserAccessServiceProvider"]);

        $handle = fopen(base_path('routes/web.php'), 'a');
        if ($handle) {
            fwrite($handle, "\n// UserAccess Dynamic Routes\n");
            fwrite($handle, "require __DIR__.'/UserAccessDynamicRoutes.php';\n");
            fclose($handle);
        } else {
            $this->error('Could not open routes/web.php for writing.');
        }

        $handle = fopen(base_path('composer.json'), 'r');
        $composerJson = json_decode(fread($handle, filesize('composer.json')), true);
        $composerJson['scripts']['remove-useraccess'] = [
			"@php artisan useraccess:remove",
			"composer remove techaxion/user-roleright-laravel"
		];
        $handle = fopen(base_path('composer.json'), 'w');
        fwrite($handle, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        fclose($handle);
        $this->info('Package installation complete!');
    }
}
