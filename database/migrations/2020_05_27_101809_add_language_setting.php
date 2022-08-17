<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(\App\Setting::where('key','config_language')->first()){
            \App\Setting::where('key','config_language')->update(['value'=>'en']);
        }
        else{
            \App\Setting::create([
                'key'=>'config_language',
                'value'=>'en',
                'type'=>'text'
            ]);
        }
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
