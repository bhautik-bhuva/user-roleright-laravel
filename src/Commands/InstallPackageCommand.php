<?php

namespace Techaxion\UserAccess\Commands;

use Illuminate\Console\Command;

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
        // Run migrations
        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/database/migrations/create_module_action_table.php', '--force' => true]);
        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/database/migrations/create_role_action_table.php', '--force' => true]);
        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/database/migrations/create_right_action_table.php', '--force' => true]);

        include_once __DIR__.'/../database/seeders/ModuleActionDataSeeder.php';
        $this->call('db:seed', ['--class' => 'Techaxion\\UserAccess\\Database\\Seeders\\ModuleActionDataSeeder', '--force' => true]);

        // Other tasks (e.g., seeding, publishing configs)
        $this->call('vendor:publish', ['--provider' => "Techaxion\\UserAccess\\UserAccessServiceProvider"]);

        $this->info('Package installation complete!');
    }
}
