<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class CurrencyNameSeeder extends Seeder
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
                ['key'=>'general_currency_name','type'=>'text','value'=>'dollars'],
                ['key'=>'general_currency_code','type'=>'text','value'=>'USD'],
            ]);
    }
}
