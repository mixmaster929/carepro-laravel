<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFtToCandidateFieldUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_field_user', function (Blueprint $table) {
            $name = $table->getTable();
            $prefix = \Illuminate\Support\Facades\DB::getTablePrefix();
            $name = $prefix.$name;
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE {$name} ADD FULLTEXT full(value)");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_field_user', function (Blueprint $table) {
            //
        });
    }
}
