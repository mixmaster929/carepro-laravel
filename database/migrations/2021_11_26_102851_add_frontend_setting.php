<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFrontendSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Setting::where('key','frontend_status')->first();
        if(!$setting){
            \App\Setting::insert(
                [
                    ['key'=>'frontend_status','type'=>'select','options'=>'1=Enabled,0=Disabled','value'=>'1'],
                ]
            );
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
