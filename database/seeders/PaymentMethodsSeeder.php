<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\PaymentMethod::create([
            'id'=>1,
            'name'=>'Paypal',
            'code'=>'paypal',
            'sort_order'=>1,
            'method_label'=>'Paypal'
        ]);

        \App\PaymentMethod::create([
            'id'=>2,
            'name'=>'Stripe',
            'code'=>'stripe',
            'sort_order'=>1,
            'method_label'=>'Stripe'
        ]);

        \App\PaymentMethod::create([
            'id'=>3,
            'name'=>'2Checkout',
            'code'=>'twocheckout',
            'sort_order'=>1,
            'method_label'=>'2Checkout'
        ]);

        \App\PaymentMethod::create([
            'id'=>4,
            'name'=>'Bank Transfer',
            'status'=>1,
            'code'=>'bank',
            'translate'=>1,
            'method_label'=>'Bank Transfer',
            'sort_order'=>1
        ]);

        \App\PaymentMethod::create([
            'id'=>5,
            'name'=>'Paystack',
            'code'=>'paystack',
            'sort_order'=>1,
            'method_label'=>'paystack'
        ]);

        \App\PaymentMethod::create([
            'id'=>6,
            'name'=>'Rave',
            'code'=>'rave',
            'sort_order'=>1,
            'method_label'=>'Rave'
        ]);

        //now add fields

        //bank
        \App\PaymentMethodField::create([
            'key'=>'details',
            'payment_method_id'=>4,
            'type'=>'textarea'
        ]);

        //paypal

        \App\PaymentMethodField::create([
            'key'=>'client_id',
            'payment_method_id'=>1,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret',
            'payment_method_id'=>1,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'mode',
            'payment_method_id'=>1,
            'type'=>'select',
            'options'=>'live=Live,sandbox=Sandbox'
        ]);

        //stripe
        \App\PaymentMethodField::create([
            'key'=>'public_key',
            'payment_method_id'=>2,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret_key',
            'payment_method_id'=>2,
            'type'=>'text'
        ]);

        //2checkout
        \App\PaymentMethodField::create([
            'key'=>'account_number',
            'payment_method_id'=>3,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret_word',
            'payment_method_id'=>3,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'mode',
            'payment_method_id'=>3,
            'type'=>'select',
            'options'=>'live=Live,sandbox=Sandbox'
        ]);

        //paystack

        \App\PaymentMethodField::create([
            'key'=>'public_key',
            'payment_method_id'=>5,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret_key',
            'payment_method_id'=>5,
            'type'=>'text'
        ]);

        //rave
        \App\PaymentMethodField::create([
            'key'=>'public_key',
            'payment_method_id'=>6,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'secret_key',
            'payment_method_id'=>6,
            'type'=>'text'
        ]);

        \App\PaymentMethodField::create([
            'key'=>'mode',
            'payment_method_id'=>6,
            'type'=>'select',
            'options'=>'live=Live,test=Test'
        ]);
    }
}
