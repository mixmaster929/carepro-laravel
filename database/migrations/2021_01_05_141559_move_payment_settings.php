<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MovePaymentSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $gateways = \App\PaymentMethod::get();

        foreach ($gateways as $gateway){
            $fields = $gateway->paymentMethodFields()->get();
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
