<?php

use App\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveCaptchaValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oSetting = Setting::where('key','general_employer_captcha')->first();
        if($oSetting){
            Setting::where('key','captcha_employer_captcha')->update(['value'=>$oSetting->value]);
        }

        $oSetting = Setting::where('key','general_candidate_captcha')->first();
        if($oSetting){
            Setting::where('key','captcha_candidate_captcha')->update(['value'=>$oSetting->value]);
        }

        Setting::where('key','captcha_type')->update(['value'=>'image']);

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
