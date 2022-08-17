<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class TestPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\PermissionGroup::insert([
            [
                'name'=>'tests',
                'sort_order'=>'12',
                'id'=>'12'
            ]
        ]);

        \App\Permission::insert([
            [
                'name'=>'view_tests',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'view_test',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'create_test',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'edit_test',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'delete_test',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'view_test_results',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'view_test_questions',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'view_test_question',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'create_test_question',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'edit_test_question',
                'permission_group_id'=>'12'
            ],
            [
                'name'=>'delete_test_question',
                'permission_group_id'=>'12'
            ],

        ]);

    }
}
