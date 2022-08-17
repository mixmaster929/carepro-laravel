<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class InvoiceCategoryPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Permission::insert([
            [
                'name'=>'view_invoice_categories',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'view_invoice_category',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'create_invoice_category',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'edit_invoice_category',
                'permission_group_id'=>'4'
            ],
            [
                'name'=>'delete_invoice_category',
                'permission_group_id'=>'4'
            ],

        ]);
    }
}
