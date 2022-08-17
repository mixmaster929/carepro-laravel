<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployerFieldUserPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employer_field_user', function (Blueprint $table) {
            $table->unsignedBigInteger('employer_field_id');
            $table->unsignedBigInteger('user_id');
            $table->text('value')->nullable();
            $table->foreign('employer_field_id')->references('id')->on('employer_fields')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employer_field_user');
    }
}
