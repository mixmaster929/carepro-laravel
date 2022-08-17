<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsGatewayFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_gateway_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('sms_gateway_id');
            $table->foreign('sms_gateway_id')->references('id')->on('sms_gateways')->onDelete('cascade');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type');
            $table->text('options')->nullable();
            $table->string('placeholder')->nullable();
            $table->integer('sort_order')->nullable();
            $table->string('class')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_gateway_fields');
    }
}
