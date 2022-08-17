<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveSmsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $gateways = \App\SmsGateway::get();

        foreach ($gateways as $gateway){
            $fields = $gateway->smsGatewayFields()->get();
            $data = [];
            foreach ($fields as $field){
                $data[$field->key] = $field->value;
            }

            $data = serialize($data);
            $gateway->settings = $data;
            $gateway->save();
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
