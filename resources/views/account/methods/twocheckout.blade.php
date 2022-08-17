@extends('account.billing.checkout')
@section('payment-form')

    <form id="2checkoutform"
    @if(paymentSetting(3,'mode')=='sandbox')
                action="https://sandbox.2checkout.com/checkout/purchase"
                @else
            action='https://www.2checkout.com/checkout/purchase'
          @endif
          method='post'>
        <input type='hidden' name='sid' value='{{ paymentSetting(3,'account_number') }}' />
        <input type="hidden" name="currency_code" value="{{ strtoupper(setting('general_currency_code')) }}"/>
        <input type='hidden' name='mode' value='2CO' />
        <input type='hidden' name='li_0_type' value='product' />
        <input type='hidden' name='li_0_name' value='{{ $invoice->title }}' />
        <input type='hidden' name='li_0_price' value='{{ $invoice->amount }}' />
        <input type='hidden' name='x_receipt_link_url' value='{{ route('user.callback',['code'=>$invoice->paymentMethod->code]) }}' />

        <input class="btn btn-primary rounded" name='submit' type='submit' value='@lang('site.checkout')' />

        @if($address)

            <input type='hidden' name='card_holder_name' value='{{ $address->name }}' />
            <input type='hidden' name='street_address' value='{{ $address->address }}' />
            <input type='hidden' name='street_address2' value='{{ $address->address2 }}' />
            <input type='hidden' name='city' value='{{ $address->city }}' />
            <input type='hidden' name='state' value='{{ $address->state }}' />
            <input type='hidden' name='zip' value='{{ $address->zip }}' />
            <input type='hidden' name='country' value='{{ strtoupper($address->country->iso_code_3) }}' />
            <input type='hidden' name='email' value='{{ $invoice->user->email }}' />
            <input type='hidden' name='phone' value='{{ $address->phone }}' />

            @endif

        <script src="https://www.2checkout.com/static/checkout/javascript/direct.min.js"></script>
    </form>

@endsection



