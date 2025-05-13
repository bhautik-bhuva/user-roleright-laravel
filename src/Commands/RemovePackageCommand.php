<?php

namespace Techaxion\UserAccess\Commands;

use Illuminate\Console\Command;

class RemovePackageCommand extends Command
{
    protected $signature = 'useraccess:remove';
    protected $description = 'remove the UserAccess tables and data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Running package removal...');

        // Run migrations
        $this->call('migrate:reset', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/database/migrations', '--force' => true]);

        if (file_exists(base_path('routes/UserAccessDynamicRoutes.php'))) {
            $fileContent = file_get_contents(base_path('routes/web.php'));
            $fileContent = str_replace(["// UserAccess Dynamic Routes\n","require __DIR__.'/UserAccessDynamicRoutes.php';\n"], "", $fileContent);
            $handle = fopen(base_path('routes/web.php'), 'w');
            if ($handle) {
                fwrite($handle, $fileContent);
                fclose($handle);
                unlink(base_path('routes/UserAccessDynamicRoutes.php'));
            } else {
                $this->error('Could not open routes/web.php for writing.');
            }
        }

        $handle = fopen(base_path('composer.json'), 'r');
        $composerJson = json_decode(fread($handle, filesize('composer.json')), true);
        unset($composerJson['scripts']['remove-useraccess']);
        $handle = fopen(base_path('composer.json'), 'w');
        fwrite($handle, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        fclose($handle);

        $this->info('Package removal complete!');
    }
}
