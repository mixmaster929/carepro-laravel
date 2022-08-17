<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class BlogPermissionSeeder extends Seeder
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
                'name'=>'blog',
                'sort_order'=>'13',
                'id'=>'13'
            ]
        ]);


        \App\Permission::insert([
            [
                'name'=>'view_blogs',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'view_blog',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'create_blog',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'edit_blog',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'delete_blog',
                'permission_group_id'=>'13'
            ],
            [
                'name'=>'manage_blog_categories',
                'permission_group_id'=>'13'
            ]

        ]);

    }
}
