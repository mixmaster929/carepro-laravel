<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class GenericSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Setting::insert(
            [

                ['key'=>'social_callback_urls','type'=>'include','options'=>'admin.settings.includes.social-callbacks','value'=>'0'],
            ]
        );

    }
}
