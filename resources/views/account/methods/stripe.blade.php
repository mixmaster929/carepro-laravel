@extends('account.billing.checkout')
@section('payment-form')
    <form action="{{ route('user.callback',['code'=>$invoice->paymentMethod->code]) }}" method="POST">
        <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="{{ paymentSetting(2,'public_key') }}"
                data-currency="{{ setting('general_currency_code') }}"
                data-amount="{{ ($invoice->amount * 100) }}"
                data-name="{{ setting('general_site_name') }}"
                data-description="{{ $invoice->title }}"
                data-email="{{ $invoice->user->email }}"
                @if(!empty(setting('image_logo')))
                data-image="{{ asset(setting('image_logo')) }}"
                @endif
                data-locale="auto">
        </script>
    </form>
@endsection

