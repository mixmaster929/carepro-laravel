<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class TestResultPermissionSeeder extends Seeder
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
                'name'=>'delete_test_result',
                'permission_group_id'=>'12'
            ]
        ]);

    }
}
