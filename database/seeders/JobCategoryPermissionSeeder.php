<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class JobCategoryPermissionSeeder extends Seeder
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
                'name'=>'view_vacancy_categories',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'view_vacancy_category',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'create_vacancy_category',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'edit_vacancy_category',
                'permission_group_id'=>'5'
            ],
            [
                'name'=>'delete_vacancy_category',
                'permission_group_id'=>'5'
            ],

        ]);
    }
}
