<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoticeSettings extends Migration
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
                ['key'=>'general_candidate_dashboard_notice','type'=>'textarea','class'=>'rte'],
                ['key'=>'general_employer_dashboard_notice','type'=>'textarea','class'=>'rte'],
            ]);


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
