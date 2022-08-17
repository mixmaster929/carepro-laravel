<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveInvoiceSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $keys = [
          'order_enable_invoice','order_invoice_amount','order_invoice_title','order_invoice_description','order_instructions','order_invoice_category'
        ];

        foreach($keys as $value){
            \App\Setting::where('key',$value)->delete();
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
