@extends('account.billing.checkout')
@section('payment-form')
    <form>
        <a class="flwpug_getpaid"
           data-PBFPubKey="{{ paymentSetting(6,'public_key') }}"
           data-txref="{{ uniqid() }}"
           data-amount="{{ $invoice->amount }}"
           data-customer_email="{{ $invoice->user->email }}"
           data-customer_phonenumber="{{ $invoice->user->telephone }}"
           data-currency="{{ setting('general_currency_code') }}"
           data-customer_firstname="{{ $invoice->user->name }}"
           data-custom_title="{{ $invoice->title }}"
           data-custom_description="{{ $invoice->description }}"
           @if(!empty(setting('image_logo')))
           data-custom_logo="{{ asset(setting('image_logo')) }}"
           @endif
           data-redirect_url="{{ route('user.callback',['code'=>$invoice->paymentMethod->code]) }}"></a>


        @if(paymentSetting(6,'mode')=='test')
        <script type="text/javascript" src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
        @else
        <script type="text/javascript" src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
        @endif
    </form>


@endsection



