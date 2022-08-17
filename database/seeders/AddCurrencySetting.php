<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class AddCurrencySetting extends Seeder
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
                ['key'=>'general_currency_symbol','type'=>'text','value'=>'$'],
            ]);
    }
}
