<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class OrderCommentPermissionSeeder extends Seeder
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
                'name'=>'view_order_comments',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'view_order_comment',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'create_order_comment',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'edit_order_comment',
                'permission_group_id'=>'1'
            ],
            [
                'name'=>'delete_order_comment',
                'permission_group_id'=>'1'
            ]
        ]);
    }
}
