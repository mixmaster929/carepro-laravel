@extends('account.billing.checkout')
@section('payment-form')
{!! clean( check( nl2br(clean(paymentSetting(4,'details')))) ) !!}
    <div>
        <a href="{{ route('user.callback',['code'=>$invoice->paymentMethod->code]) }}" class="btn btn-block btn-primary">@lang('site.complete')</a>
    </div>
@endsection



