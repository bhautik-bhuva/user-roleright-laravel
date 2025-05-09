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
        $this->call('migrate:rollback', ['--path' => 'vendor/techaxion/user-roleright-laravel/src/database/migrations']);
        
        $this->info('Package removal complete!');
    }
}
