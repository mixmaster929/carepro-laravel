<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class AdminRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create seeder for super administrator
        $permissions = \App\Permission::get();

        $adminRole = \App\AdminRole::find(1);
        $permissionList = [];
        foreach($permissions as $permission){
            $permissionList[] = $permission->id;
        }

        $adminRole->permissions()->sync($permissionList);


        //create seeder for administrator
        $permissions = \App\Permission::where('id','>',2)->get();

        $adminRole = \App\AdminRole::find(2);
        $permissionList = [];
        foreach($permissions as $permission){
            $permissionList[] = $permission->id;
        }

        $adminRole->permissions()->sync($permissionList);
    }
}
