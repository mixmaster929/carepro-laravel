<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFtToSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms', function (Blueprint $table) {
            $name = $table->getTable();
            $prefix = \Illuminate\Support\Facades\DB::getTablePrefix();
            $name = $prefix.$name;
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE {$name} ADD FULLTEXT full(comment,message)");

            $table->unsignedBigInteger('sms_gateway_id');
            $table->foreign('sms_gateway_id')->references('id')->on('sms_gateways')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms', function (Blueprint $table) {
            //
        });
    }
}
