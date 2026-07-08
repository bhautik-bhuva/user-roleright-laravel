<?php

namespace Techaxion\UserAccess\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use File;

class UpdatePackageCommand extends Command
{
    protected $signature = 'useraccess:update';
    protected $description = 'Update the UserAccess tables';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('🔧 Updating UserAccess package ...');

        $routefile = base_path('routes/UserAccessDynamicRoutes.php');

        if (file_exists($routefile)) {
            unlink($routefile);
        }
        $assetsPath = public_path('assets/vendor/useraccess');
        if (File::exists($assetsPath)) {
            File::deleteDirectory($assetsPath);
        }
        $this->call('vendor:publish', ['--tag' => "useraccess-routes"]);

        $this->call('vendor:publish', ['--tag' => 'useraccess-assets']);
        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/Database/Migrations/create_menu_procedure.php', '--force' => true]);

        $this->call('migrate', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/Database/Migrations', '--force' => true]);

        $this->info('🎉 Package updation complete!');
    }
}
