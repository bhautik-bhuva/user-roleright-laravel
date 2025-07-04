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
        $this->info('🔧 Running UserAccess package installation...');

        // Step 1: Ask for user input
        $userTable = $this->ask("👉 Enter your user table name", 'users');
        $layoutPath = $this->ask("📁 Enter your layout Blade view path (e.g., resource/views/layouts.blade.php)", 'layouts.blade.php');
        $yieldSection = $this->ask("📦 Enter the @yield('your_section_name') section name in your main layout (e.g., content)", 'content');

        // Step 2: Write to config/useraccess.php
        $configContent = <<<PHP
        <?php

        return [
            'user_table' => '$userTable',
            'layout_path' => '$layoutPath',
            'yield_container' => '$yieldSection',
        ];
        PHP;

        $configPath = config_path('useraccess.php');
        File::put($configPath, $configContent);
        $this->info('✅ Configuration saved to config/useraccess.php');

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

        // Step 8: Add route to web.php
        $webPath = base_path('routes/web.php');
        if (File::exists($webPath) && strpos(File::get($webPath), 'UserAccessDynamicRoutes.php') === false) {
            File::append($webPath, "\n// UserAccess Dynamic Routes\nrequire __DIR__.'/UserAccessDynamicRoutes.php';\n");
            $this->info('✅ Route added to routes/web.php');
        } else {
            $this->warn('⚠️ routes/web.php already contains the dynamic route or could not be modified.');
        }

        // Step 9: Add uninstall script to composer.json
        $composerPath = base_path('composer.json');
        $composerJson = json_decode(file_get_contents($composerPath), true);
        $composerJson['scripts']['remove-useraccess'] = [
            "@php artisan useraccess:remove",
            "composer remove techaxion/user-roleright-laravel"
        ];
        file_put_contents($composerPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->info('✅ Added remove-useraccess script to composer.json');

        $this->info('🎉 Package installation complete!');
    }
}
