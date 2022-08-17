<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsToOrderForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_forms', function (Blueprint $table) {
            $table->boolean('auto_invoice')->default(0);
            $table->float('invoice_amount')->nullable();
            $table->string('invoice_title')->nullable();
            $table->text('invoice_description')->nullable();
            $table->unsignedBigInteger('invoice_category_id')->nullable();
            $table->foreign('invoice_category_id')->references('id')->on('invoice_categories')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_forms', function (Blueprint $table) {
            //
        });
    }
}
