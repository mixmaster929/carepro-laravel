<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixMissingRegistration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(\App\Setting::where('key','general_enable_employer_registration')->count()==0){
            \App\Setting::insert(
                [
                    ['key'=>'general_enable_employer_registration','type'=>'radio','options'=>'1=Yes,0=No','value'=>1]
                ]
            );
        }

        if(\App\Setting::where('key','general_enable_candidate_registration')->count()==0){
            \App\Setting::insert(
                [
                     ['key'=>'general_enable_candidate_registration','type'=>'radio','options'=>'1=Yes,0=No','value'=>1]

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
