<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class InvoiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\InvoiceCategory::insert([
            ['name'=>'Mobilization Fees','sort_order'=>1],
            ['name'=>'Placement Fees','sort_order'=>2]
        ]);
    }
}
