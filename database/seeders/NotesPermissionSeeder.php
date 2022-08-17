<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class NotesPermissionSeeder extends Seeder
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
                'name'=>'view_candidate_attachments',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'view_candidate_attachment',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'create_candidate_attachment',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'edit_candidate_attachment',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'delete_candidate_attachment',
                'permission_group_id'=>'3'
            ],
            [
                'name'=>'view_employer_attachments',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'view_employer_attachment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'create_employer_attachment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'edit_employer_attachment',
                'permission_group_id'=>'2'
            ],
            [
                'name'=>'delete_employer_attachment',
                'permission_group_id'=>'2'
            ]
        ]);
    }
}
