<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class OrderSettingCategorySeeder extends Seeder
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
                ['key'=>'order_invoice_category','type'=>'include','options'=>'admin.settings.includes.invoice-category'],
            ]);
    }
}
