@extends('account.billing.checkout')
@section('payment-form')
    <form id="paymentForm" action="{{ route('user.callback',['code'=>$invoice->paymentMethod->code]) }}" method="POST" >
        <script
                src="https://js.paystack.co/v1/inline.js"
                data-key="{{ paymentSetting(5,'public_key') }}"
                data-email="{{ $invoice->user->email }}"
                data-amount="{{ ($invoice->amount * 100) }}"
                data-ref="{{ uniqid() }}"

                >
        </script>

    </form>
@endsection



