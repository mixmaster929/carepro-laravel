<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployerFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employer_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('employer_field_group_id')->nullable();
            $table->foreign('employer_field_group_id')->references('id')->on('employer_field_groups')->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->integer('sort_order')->nullable();
            $table->text('options')->nullable();
            $table->boolean('required')->default(0);
            $table->text('placeholder')->nullable();
            $table->boolean('enabled')->default(1);
            $table->boolean('filter')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employer_fields');
    }
}
