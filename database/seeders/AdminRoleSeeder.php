<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = new \App\AdminRole();
        $adminRole->id = 1;
        $adminRole->name = 'Super Administrator';
        $adminRole->save();

        $adminRole = new \App\AdminRole();
        $adminRole->id = 2;
        $adminRole->name = 'Administrator';
        $adminRole->save();
    }
}
