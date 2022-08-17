<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class OrderSettingsSeeder extends Seeder
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
                ['key'=>'order_instructions','type'=>'textarea','class'=>'form-control rte'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_enable_shortlist','type'=>'radio','options'=>'1=Yes,0=No','value'=>'1'],
            ]);
        //This is or auto invoice generation
        \App\Setting::insert(
            [
                ['key'=>'order_enable_invoice','type'=>'radio','options'=>'1=Yes,0=No','value'=>'1'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_invoice_amount','type'=>'text','class'=>'form-control digit'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_invoice_title','type'=>'text'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_invoice_description','type'=>'textarea'],
            ]);

        \App\Setting::insert(
            [
                ['key'=>'order_require_address','type'=>'radio','options'=>'0=No,1=Yes','value'=>'0'],
            ]);

    }
}
