<?php

namespace Techaxion\UserAccess\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use File;
use Techaxion\UserAccess\Models\Roles;
use Techaxion\UserAccess\Models\UserRoleMapping;
use Techaxion\UserAccess\Models\RolesAction;
use Techaxion\UserAccess\Models\ModuleAction;

class InitPackageConfigCommand extends Command
{
    protected $signature = 'useraccess:init-config';
    protected $description = 'Initialize the UserAccess package configuration';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        // $sessionphp = include config_path('session.php');
        // $CookieName = $sessionphp['cookie'];
        // $sessionphp = file_get_contents(dirname(__DIR__,1).'/UserAccessServiceProvider.php');
        // $sessionphp = str_replace('$sessionname=""', '$sessionname="'.$CookieName.'"', $sessionphp);
        // file_put_contents(dirname(__DIR__,1).'/UserAccessServiceProvider.php', $sessionphp);

        Artisan::call('useraccess:install');

        $SSOlogin = $this->confirm("👉 Do you want to enable SSO login?");
        if($SSOlogin){
            $this->info("SSO login enabled");
            $this->info("Please configure SSO login in .env file");
            $this->info("SSO login configuration: https://laravel.com/docs/11.x/authentication#sso");
        }

        // Ask for user input
        $userTable = $this->ask("👉 Enter your user table name", 'users');
        $assignuseremail = $this->ask("📧 Enter user email to assign super admin access", 'test@gmail.com');
        $userId = 0;
        if(isset($assignuseremail)){
            $user = \DB::table($userTable)->where('email', $assignuseremail)->first();
            if ($user) {
                $userId = $user->id;
                $this->info("User ID for {$assignuseremail}: {$userId}");
             
                $existsuperadminrole = Roles::where('name', 'Super Admin')->where('interface_access',2)->first();
                if($existsuperadminrole){
                    $roleId = $existsuperadminrole->id;
                }else{
                    $roleId = Roles::insertGetId([
                        'name' => 'Super Admin',
                        'access' => 'All',
                        'interface_access' => 2,
                        'description' => 'All Access',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                $existUserRoleMapping = UserRoleMapping::where('user_id', $userId)->where('role_id',$roleId)->first();
                if(!$existUserRoleMapping){
                    UserRoleMapping::insert([
                        'user_id' => $userId,
                        'role_id' => $roleId
                    ]);
                }
                $allActions = ModuleAction::pluck('id')->toArray();
                $insertrolaactions = [];
                foreach ($allActions as $actionId) {
                    $existUserRoleMapping = RolesAction::where('action_id', $actionId)->where('role_id',$roleId)->first();
                    if(!$existUserRoleMapping){
                        $insertrolaactions[] = [
                            'role_id' => $roleId,
                            'action_id' => $actionId
                        ];
                    }
                }
                if(count($insertrolaactions) > 0 ){
                    RolesAction::insert($insertrolaactions);
                }
            } else {
                $this->error("No user found with email: {$assignuseremail}");
            }
        }
        
        // fetch all posibile layout folder matching name "layout" and ask to choice
        $layoutFolders = File::directories(base_path('resources/views'));
        // check all sub directory for layout folder
        foreach ($layoutFolders as $folder) {
            $subFolders = File::directories($folder);
            foreach ($subFolders as $subFolder) {
                $layoutFolders[] = $subFolder;
            }
        }
        $layoutFolders = array_filter($layoutFolders, function($folder) {
            return strpos(strtolower(basename($folder)), 'layout') !== false;
        });
        $layoutFolders = array_values($layoutFolders);

        if(count($layoutFolders) == 0){
            $this->error('No layout folders found');
            $layoutPath = 'resources/views/app.blade.php';
        }else{
            $this->info('Available layout folders: ' . implode(', ', $layoutFolders));
            $layoutFolders[] = "other";
            $layoutFolder = $this->choice("📁 Choice default layout Blade view folder (e.g., resources/views/layouts)", $layoutFolders, 0);

            if($layoutFolder == "other"){
                $layoutPath = $this->ask("📁 Enter your layout Blade view path (e.g., resources/views/app.blade.php)", 'resources/views/app.blade.php');
            }else{
                $this->info($layoutFolder);
                $this->info(base_path($layoutFolder.'/*.blade.php'));
                $layoutFiles = File::glob($layoutFolder.'/*.blade.php');
                $this->info(print_r($layoutFiles));
                $layoutFiles = array_map(function($file) {
                    return str_replace(base_path(), '', $file);
                }, $layoutFiles);
                $this->info('Available layout files: ' . implode(', ', $layoutFiles));
                $layoutPath = $this->choice("📁 Enter your layout Blade view path (e.g., resources/views/layouts/app.blade.php)", $layoutFiles, 0);
            }
        }
        
        $yieldSection = $this->ask("📦 Enter the @yield('your_section_name') section name in your main layout (e.g., content)", 'content');

        $frontend = $this->choice("📁 Choose CSS framework (e.g., bootstrap)", ['bootstrap', 'tailwind'], 0);
        // $selced_frontend = $this->choice("📁 Choice default layout Blade view folder (e.g., resources/views/layouts)", $layoutFolders, 0);

        if ($frontend == 'tailwind') {
            $this->info('🚀 Running "npm install" and "npm run build" for Tailwind CSS...');
            shell_exec('npm install');
            shell_exec('npm run build');
            $this->info('✅ Tailwind assets compiled!');
        }
            
        // DIRECTORY_SEPARATOR
        $layoutPath = str_replace(DIRECTORY_SEPARATOR, "/", $layoutPath);
        $layoutPath = explode("/",$layoutPath);        
        $layoutPath = join(DIRECTORY_SEPARATOR, $layoutPath);
        
        $configContent = json_encode([
            "layout_path" => base_path("$layoutPath"),
            "layout_file" => str_replace(".blade.php","",basename("$layoutPath")),
            "yield_container" => "$yieldSection",
            "user_table" => "$userTable",
            "superadmin_userid" => "$userId",
            "menu_migrated" => "no",
            "frontend" => "$frontend"
        ]);

        $this->info($configContent);

        $pluginPath = dirname(__DIR__,1). '/configuration.json'; 
        $this->info('✅ Dynamic setting file path is '.$pluginPath);

        File::put($pluginPath, $configContent);
        $this->info('✅ Configuration saved to configuration.json');

        if (!empty($layoutPath)) {
            $filePath = file_get_contents(base_path($layoutPath));
            // body tag replace with @stack('scripts')
            if (strpos($filePath, '@stack("styles")'."\n".'</head>') === false) {
                $filePath = str_replace('</head>', '@stack("styles")'."\n".'</head>', $filePath);
                File::put(base_path($layoutPath), $filePath);
            }
            if (strpos($filePath, '@stack("scripts")'."\n".'</body>') === false) {
                $filePath = str_replace('</body>', '@stack("scripts")'."\n".'</body>', $filePath);
                File::put(base_path($layoutPath), $filePath);
            }
        }
        $this->info('🎉 Package initialization complete!');
    }
}
