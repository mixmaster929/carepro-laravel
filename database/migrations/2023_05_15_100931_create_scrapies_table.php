<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrapiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrapies', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('kvk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scrapies');
    }
}
