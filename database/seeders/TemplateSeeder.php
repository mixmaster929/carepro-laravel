<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Template::create([
           'name'=> 'Application',
            'enabled'=>1,
            'directory'=>'application'
        ]);
    }
}
