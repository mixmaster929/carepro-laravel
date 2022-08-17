<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class InterviewPermissionSeeder extends Seeder
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
                'name'=>'interviews',
                'sort_order'=>'11',
                'id'=>'11'
            ]
        ]);


        \App\Permission::insert([
            [
                'name'=>'view_interviews',
                'permission_group_id'=>'11'
            ],
            [
                'name'=>'view_interview',
                'permission_group_id'=>'11'
            ],
            [
                'name'=>'create_interview',
                'permission_group_id'=>'11'
            ],
            [
                'name'=>'edit_interview',
                'permission_group_id'=>'11'
            ],
            [
                'name'=>'delete_interview',
                'permission_group_id'=>'11'
            ],

        ]);




    }
}
