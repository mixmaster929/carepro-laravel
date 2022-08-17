<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFtToUserNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_notes', function (Blueprint $table) {
            $name = $table->getTable();
            $prefix = \Illuminate\Support\Facades\DB::getTablePrefix();
            $name = $prefix.$name;
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE {$name} ADD FULLTEXT full(title,content)");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_notes', function (Blueprint $table) {
            //
        });
    }
}
