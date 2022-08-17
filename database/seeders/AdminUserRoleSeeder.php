<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class AdminUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user  = \App\User::find(1);
        $user->adminRoles()->sync([1]);
    }
}
