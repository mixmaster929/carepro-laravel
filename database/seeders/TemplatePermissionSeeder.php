<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class TemplatePermissionSeeder extends Seeder
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
            'name'=>'view_email_templates',
            'permission_group_id'=>'6'
        ] ,
            [
                'name'=>'view_email_template',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'create_email_template',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'edit_email_template',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'delete_email_template',
                'permission_group_id'=>'6'
            ],

        //sms templates
            [
                'name'=>'view_sms_templates',
                'permission_group_id'=>'7'
            ] ,
            [
                'name'=>'view_sms_template',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'create_sms_template',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'edit_sms_template',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'delete_sms_template',
                'permission_group_id'=>'7'
            ],

        ]);
    }
}
