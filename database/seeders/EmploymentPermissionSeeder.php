<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class EmploymentPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Permission::insert([
            [
                'name'=>'view_employments',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'create_employment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'edit_employment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'delete_employment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employment_comments',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employment_comment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'create_employment_comment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'edit_employment_comment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'delete_employment_comment',
                'permission_group_id'=>'2'
            ]
        ]);
    }
}
