<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->id=1;
        $user->name= 'Admin';
        $user->email = 'admin@email.com';
        $user->password = bcrypt('password');
        $user->role_id = 1;
        $user->save();
    }
}
