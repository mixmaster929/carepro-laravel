<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\PermissionGroup::insert([
            [
                'name'=>'contracts',
                'sort_order'=>'14',
                'id'=>'14'
            ]
        ]);

        \App\Permission::insert([
            [
                'name'=>'view_contracts',
                'permission_group_id'=>'14'
            ],
            [
                'name'=>'view_contract',
                'permission_group_id'=>'14'
            ],
            [
                'name'=>'create_contract',
                'permission_group_id'=>'14'
            ],
            [
                'name'=>'edit_contract',
                'permission_group_id'=>'14'
            ],
            [
                'name'=>'delete_contract',
                'permission_group_id'=>'14'
            ],
            [
                'name'=>'send_contract',
                'permission_group_id'=>'14'
            ],

        ]);






    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
