<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PermissionGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            \App\PermissionGroup::insert([
               [
                   'name'=>'orders',
                   'sort_order'=>'1',
                   'id'=>'1'
               ],
                [
                    'name'=>'employers',
                    'sort_order'=>'2',
                    'id'=>'2'
                ],
                [
                    'name'=>'candidates',
                    'sort_order'=>'3',
                    'id'=>'3'
                ],
                [
                    'name'=>'invoices',
                    'sort_order'=>'4',
                    'id'=>'4'
                ],
                [
                    'name'=>'vacancies',
                    'sort_order'=>'5',
                    'id'=>'5'
                ],
                [
                    'name'=>'emails',
                    'sort_order'=>'6',
                    'id'=>'6'
                ],
                [
                    'name'=>'text_messaging',
                    'sort_order'=>'7',
                    'id'=>'7'
                ],
                [
                    'name'=>'articles',
                    'sort_order'=>'8',
                    'id'=>'8'
                ],/*
                [
                    'name'=>'forms',
                    'sort_order'=>'9',
                    'id'=>'9'
                ],*/
                [
                    'name'=>'settings',
                    'sort_order'=>'10',
                    'id'=>'10'
                ]

            ]);
    }
}
