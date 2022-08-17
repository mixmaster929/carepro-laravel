<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaptchaSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            \App\Setting::insert(
                [
                    ['key'=>'captcha_candidate_captcha','type'=>'radio','options'=>'1=Yes,0=No','sort_order'=>'1'],
                    ['key'=>'captcha_employer_captcha','type'=>'radio','options'=>'1=Yes,0=No','sort_order'=>'2'],
                ]);


            \App\Setting::insert(
                        [
                            ['key'=>'captcha_type','type'=>'select','options'=>'image=image,google=google-recaptcha','sort_order'=>'3'],
                        ]);

            \App\Setting::insert(
                [
                    ['key'=>'captcha_recaptcha_key','type'=>'text','sort_order'=>'4'],
                    ['key'=>'captcha_recaptcha_secret','type'=>'text','sort_order'=>'5'],
                ]);



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
}
