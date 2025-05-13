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
        DB::table('module_action')->insert([
            ['name' => 'Add Role', 'controller' => 'RoleController', 'method' => 'add', 'action' => '/role/add', 'route_type' => 'get', 'menu_type' => 'Admin', 'menu_label' => 'Add Role', 'menu_status' => '0', 'menu_sequence' => 1, 'menu_order' => 6, 'menu_icon' => '', 'module_label' => 'Role Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth,verified"}'],
            ['name' => 'Edit Role', 'controller' => 'RoleController', 'method' => 'edit', 'action' => '/role/edit/{id}', 'route_type' => 'post,get', 'menu_type' => 'Admin', 'menu_label' => 'Edit Role', 'menu_status' => '0', 'menu_sequence' => 1, 'menu_order' => 7, 'menu_icon' => '', 'module_label' => 'Role Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth,verified"}'],
            ['name' => 'Role List', 'controller' => 'RoleController', 'method' => 'list', 'action' => '/role/list', 'route_type' => 'post,get', 'menu_type' => 'Admin', 'menu_label' => 'User Role', 'menu_status' => '1', 'menu_sequence' => 1, 'menu_order' => 0, 'menu_icon' => 'bx bxs-lock-open-alt me-2', 'module_label' => 'Role Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth,verified"}'],
            ['name' => 'Role List Datatable', 'controller' => 'RoleController', 'method' => 'datatable', 'action' => '/role/datatable', 'route_type' => 'post', 'menu_type' => 'Admin Backend', 'menu_label' => 'User Role List Ajax', 'menu_status' => '0', 'menu_sequence' => 1, 'menu_order' => 1, 'menu_icon' => '', 'module_label' => 'Role Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth,verified"}'],
        ]);

        DB::table('module_action')->insert([
            ['name' => 'Role List', 'controller' => 'RightController', 'method' => 'listRight', 'action' => '/right/list', 'route_type' => 'get', 'menu_type' => 'Admin', 'menu_label' => 'Role List', 'menu_status' => '0', 'menu_sequence' => 1, 'menu_order' => 0, 'menu_icon' => '', 'module_label' => 'Right Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth,verified"}'],
            ['name' => 'Load Registered Modules', 'controller' => 'RightController', 'method' => 'registeredModules', 'action' => '/right/registered-modules', 'route_type' => 'post', 'menu_type' => 'Admin', 'menu_label' => 'Load Registered Modules', 'menu_status' => '0', 'menu_sequence' => 0, 'menu_order' => 0, 'menu_icon' => '', 'module_label' => 'Right Management', 'status' => 1, 'created_date' => date('Y-m-d h:i:s'), 'extra_options' => '{"filters":"auth,verified"}']
        ]);

    }
}