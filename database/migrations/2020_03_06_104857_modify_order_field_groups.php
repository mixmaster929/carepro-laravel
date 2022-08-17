<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyOrderFieldGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_field_groups', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->unsignedBigInteger('order_form_id')->default(1);
            $table->foreign('order_form_id')->references('id')->on('order_forms')->onDelete('cascade');
            $table->char('layout')->default('v');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_field_groups', function (Blueprint $table) {
            //
        });
    }
}
