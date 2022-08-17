<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name');
            $table->string('iso_code_2');
            $table->string('iso_code_3');
            $table->string('address_format');
            $table->boolean('postcode_required')->default(0);
            $table->boolean('status')->default(1);
            $table->string('currency_name')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('symbol_left')->nullable();
            $table->string('symbol_right')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
