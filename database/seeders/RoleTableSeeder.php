<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new \App\Role();
        $role->id =1;
        $role->name = 'Admin';
        $role->save();

        $role = new \App\Role();
        $role->id =2;
        $role->name = 'Employer';
        $role->save();

        $role = new \App\Role();
        $role->id =3;
        $role->name = 'Candidate';
        $role->save();
    }
}
