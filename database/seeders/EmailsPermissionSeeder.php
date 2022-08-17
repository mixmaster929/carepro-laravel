<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class EmailsPermissionSeeder extends Seeder
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
               'name'=>'view_emails',
               'permission_group_id'=>'6'
           ] ,
            [
                'name'=>'view_email',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'create_email',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'edit_email',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'delete_email',
                'permission_group_id'=>'6'
            ],
            //email resources
            [
                'name'=>'view_email_resources',
                'permission_group_id'=>'6'
            ] ,
            [
                'name'=>'view_email_resource',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'create_email_resource',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'edit_email_resource',
                'permission_group_id'=>'6'
            ],
            [
                'name'=>'delete_email_resource',
                'permission_group_id'=>'6'
            ],
            //text messages
            [
                'name'=>'view_text_messages',
                'permission_group_id'=>'7'
            ] ,
            [
                'name'=>'view_text_message',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'create_text_message',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'edit_text_message',
                'permission_group_id'=>'7'
            ],
            [
                'name'=>'delete_text_message',
                'permission_group_id'=>'7'
            ]



        ]);
    }
}
