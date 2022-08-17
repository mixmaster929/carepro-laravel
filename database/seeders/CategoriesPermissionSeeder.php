<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class CategoriesPermissionSeeder extends Seeder
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
                'name'=>'view_categories',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'view_category',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'create_category',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'edit_category',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'delete_category',
                'permission_group_id'=>'3'
            ],

        ]);
    }
}
