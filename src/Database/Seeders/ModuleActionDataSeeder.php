<?php

namespace Techaxion\UserAccess\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleActionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $controller = 'Techaxion\UserAccess\Controllers\\';
        // Roles
        DB::table('module_action')->insert([
            ['name' => 'Role List', 'controller' => $controller.'RoleController', 'method' => 'list', 'action' => '/role/list', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Role List', 'menu_status' => '1', 'menu_sequence' => 1, 'menu_order' => 0, 'menu_icon' => 'fas fa-lock me-2', 'module_label' => 'Role Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.role.list", "prefix":"useraccess"}'],
            ['name' => 'Create Role', 'controlle  r' => $controller.'RoleController', 'method' => 'create', 'action' => '/role/create', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Create Role', 'menu_status' => '1', 'menu_sequence' => 1, 'menu_order' => 1, 'menu_icon' => '', 'module_label' => 'Role Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.role.create", "prefix":"useraccess"}'],
            ['name' => 'Store Role', 'controller' => $controller.'RoleController', 'method' => 'store', 'action' => '/role/store', 'route_type' => 'post', 'menu_type' => '1,2,3', 'menu_label' => 'Store Role', 'menu_status' => '0', 'menu_sequence' => 1, 'menu_order' => 2, 'menu_icon' => '', 'module_label' => 'Role Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.role.store", "prefix":"useraccess"}'],
            ['name' => 'Edit Role', 'controller' => $controller.'RoleController', 'method' => 'edit', 'action' => '/role/edit/{role}', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Edit Role', 'menu_status' => '0', 'menu_sequence' => 1, 'menu_order' => 3, 'menu_icon' => '', 'module_label' => 'Role Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.role.edit", "prefix":"useraccess"}'],
            ['name' => 'Update Role', 'controller' => $controller.'RoleController', 'method' => 'update', 'action' => '/role/update/{role}', 'route_type' => 'put', 'menu_type' => '1,2,3', 'menu_label' => 'Update Role', 'menu_status' => '0', 'menu_sequence' => 1, 'menu_order' => 4, 'menu_icon' => '', 'module_label' => 'Role Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.role.update", "prefix":"useraccess"}'],
        ]);
        // User & Rights
        DB::table('module_action')->insert([
            ['name' => 'User List', 'controller' => $controller.'UserController', 'method' => 'list', 'action' => '/user/list', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'User List', 'menu_status' => '1', 'menu_sequence' => 2, 'menu_order' => 0, 'menu_icon' => 'fas fa-users me-2', 'module_label' => 'User and Right Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.user.list", "prefix":"useraccess"}'],
            ['name' => 'Create User', 'controller' => $controller.'UserController', 'method' => 'create', 'action' => '/user/create', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Create User', 'menu_status' => '1', 'menu_sequence' => 2, 'menu_order' => 1, 'menu_icon' => '', 'module_label' => 'User and Right Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.user.create", "prefix":"useraccess"}'],
            ['name' => 'Store User', 'controller' => $controller.'UserController', 'method' => 'store', 'action' => '/user/store', 'route_type' => 'post', 'menu_type' => '1,2,3', 'menu_label' => 'Store User', 'menu_status' => '0', 'menu_sequence' => 2, 'menu_order' => 2, 'menu_icon' => '', 'module_label' => 'User and Right Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.user.store", "prefix":"useraccess"}'],
            ['name' => 'Edit User and Rights', 'controller' => $controller.'UserController', 'method' => 'edit', 'action' => '/user/edit/{user}', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Edit User and Rights', 'menu_status' => '0', 'menu_sequence' => 2, 'menu_order' =>3, 'menu_icon' => '', 'module_label' => 'User and Right Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.user.edit", "prefix":"useraccess"}'],
            ['name' => 'Update User', 'controller' => $controller.'UserController', 'method' => 'update', 'action' => '/user/update/{user}', 'route_type' => 'put', 'menu_type' => '1,2,3', 'menu_label' => 'Update User', 'menu_status' => '0', 'menu_sequence' => 2, 'menu_order' => 4, 'menu_icon' => '', 'module_label' => 'User and Right Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.user.update", "prefix":"useraccess"}'],
            ['name' => 'Delete User', 'controller' => $controller.'UserController', 'method' => 'delete', 'action' => '/user/delete/{user}', 'route_type' => 'delete', 'menu_type' => '1,2,3', 'menu_label' => 'Delete User', 'menu_status' => '0', 'menu_sequence' => 2, 'menu_order' => 5, 'menu_icon' => '', 'module_label' => 'User and Right Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.user.delete", "prefix":"useraccess"}']
        ]);
        // Menus
        DB::table('module_action')->insert([
            ['name' => 'Menu List', 'controller' => $controller.'MenuController', 'method' => 'list', 'action' => '/menu/list', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Menu List', 'menu_status' => '1', 'menu_sequence' => 3, 'menu_order' => 0, 'menu_icon' => 'fas fa-list me-2', 'module_label' => 'Menu Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.menu.list", "prefix":"useraccess"}'],
            ['name' => 'Create Menu', 'controller' => $controller.'MenuController', 'method' => 'create', 'action' => '/menu/create', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Create Menu', 'menu_status' => '1', 'menu_sequence' => 3, 'menu_order' => 1, 'menu_icon' => '', 'module_label' => 'Menu Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.menu.create", "prefix":"useraccess"}'],
            ['name' => 'Store Menu', 'controller' => $controller.'MenuController', 'method' => 'store', 'action' => '/menu/store', 'route_type' => 'post', 'menu_type' => '1,2,3', 'menu_label' => 'Store Menu', 'menu_status' => '0', 'menu_sequence' => 3, 'menu_order' => 2, 'menu_icon' => '', 'module_label' => 'Menu Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.menu.store", "prefix":"useraccess"}'],
            ['name' => 'Method List Ajax', 'controller' => $controller.'MenuController', 'method' => 'methodNames', 'action' => '/menu/methodNames', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Method List Ajax', 'menu_status' => '0', 'menu_sequence' => 3, 'menu_order' => 3, 'menu_icon' => '', 'module_label' => 'Menu Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.menu.methodNames", "prefix":"useraccess"}'],
            ['name' => 'Edit Menu', 'controller' => $controller.'MenuController', 'method' => 'edit', 'action' => '/menu/edit/{moduleAction}', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Edit Menu', 'menu_status' => '0', 'menu_sequence' => 3, 'menu_order' => 4, 'menu_icon' => '', 'module_label' => 'Menu Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.menu.edit", "prefix":"useraccess"}'],
            ['name' => 'Update Menu', 'controller' => $controller.'MenuController', 'method' => 'update', 'action' => '/menu/update/{moduleAction}', 'route_type' => 'put', 'menu_type' => '1,2,3', 'menu_label' => 'Update Menu', 'menu_status' => '0', 'menu_sequence' => 3, 'menu_order' => 5, 'menu_icon' => '', 'module_label' => 'Menu Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.menu.update", "prefix":"useraccess"}'],
            ['name' => 'Delete Menu', 'controller' => $controller.'MenuController', 'method' => 'delete', 'action' => '/menu/delete/{moduleAction}', 'route_type' => 'delete', 'menu_type' => '1,2,3', 'menu_label' => 'Delete Menu', 'menu_status' => '0', 'menu_sequence' => 3, 'menu_order' => 6, 'menu_icon' => '', 'module_label' => 'Menu Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.menu.delete", "prefix":"useraccess"}']
        ]);
        // Menus
        DB::table('module_action')->insert([
            ['name' => 'Useraccess Setting', 'controller' => $controller.'SettingController', 'method' => 'useraccessindex', 'action' => '/setting', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Useraccess Setting', 'menu_status' => '1', 'menu_sequence' => 4, 'menu_order' => 0, 'menu_icon' => 'fas fa-cog me-2', 'module_label' => 'Useraccess Setting', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.setting", "prefix":"useraccess"}'],
            ['name' => 'Menu json', 'controller' => $controller.'SettingController', 'method' => 'menujson', 'action' => '/menu/json', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Menu json', 'menu_status' => '0', 'menu_sequence' => 4, 'menu_order' => 1, 'menu_icon' => '', 'module_label' => 'Useraccess Setting', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.menu.json", "prefix":"useraccess"}'],
            ['name' => 'Menu Migrate', 'controller' => $controller.'SettingController', 'method' => 'menumigrate', 'action' => '/menu/migrate', 'route_type' => 'get', 'menu_type' => '1,2,3', 'menu_label' => 'Menu Migrate', 'menu_status' => '0', 'menu_sequence' => 4, 'menu_order' => 2, 'menu_icon' => '', 'module_label' => 'Useraccess Setting', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.menu.migrate", "prefix":"useraccess"}'],
            ['name' => 'Update Useraccess Setting', 'controller' => $controller.'SettingController', 'method' => 'updatesetting', 'action' => '/update/setting', 'route_type' => 'put', 'menu_type' => '1,2,3', 'menu_label' => 'Update Useraccess Setting', 'menu_status' => '0', 'menu_sequence' => 4, 'menu_order' => 3, 'menu_icon' => '', 'module_label' => 'Useraccess Setting', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth","route_name":"useraccess.update.setting", "prefix":"useraccess"}']
        ]);

        // Access for
        DB::table('access_for')->insert([
            ['name' => 'All','route_type' => 'resource,post,get,put,patch,delete'],
            ['name' => 'Super Admin','route_type' => 'resource,post,get,put,patch,delete'],
            ['name' => 'Admin','route_type' => 'resource,post,get,put,patch'],
            ['name' => 'Guest','route_type' => 'get'],
            ['name' => 'None','route_type' => '']
        ]);
    }
}
