@extends($userLayout)

@section('header')
<link href="{{ asset('css/stripe.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title',__('site.checkout'))

@section('breadcrumb')
        <li  class="breadcrumb-item"><a href="{{ route('user.invoice.cart') }}">@lang('site.cart')</a></li>
        <li class="breadcrumb-item">@lang('site.checkout')</li>
@endsection

@section('content')
    <a href="{{ route('user.invoice.cart')  }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
    <br />
    <br />

    <div class="row">

    @if($address)
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('site.billing-address')</h3>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">

                            <h4>@lang('site.selected-address')</h4>
                            <p><strong>@lang('site.name'):</strong> {{ $address->name }}</p>
                            <p><strong>@lang('site.telephone'):</strong> {{ $address->phone }}</p>
                            <p><strong>@lang('site.street-address'):</strong> {{ $address->address }}</p>
                            @if(!empty($address->address2))
                                <p><strong>@lang('site.street-address-2'):</strong> {{ $address->address2 }}</p>
                            @endif
                            <p><strong>@lang('site.city'):</strong> {{ $address->city }}</p>
                            <p><strong>@lang('site.state'):</strong> {{ $address->state }}</p>
                            <p><strong>@lang('site.zip'):</strong> {{ $address->zip }}</p>
                            <p><strong>@lang('site.country'):</strong> {{ $address->country->name }}</p>

                    </div>

                    <div class="col-md-6">
                    <h4>@lang('site.change-address')</h4>
                        <div  >

                            <div class="int_tpmb" >
                                <a class="btn btn-primary btn-sm" href="{{ route('user.invoice.change-address') }}">@lang('site.select-saved-address')</a>

                            </div>

                            <div class="int_tpmb"   >
                                <a class="btn btn-success btn-sm" href="{{ route('user.billing-address.create') }}">@lang('site.create-new-address')</a>

                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>


    </div>
    @endif

    <div class="@if($address) col-md-6 @else col-md-8 offset-md-2 @endif">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-credit-card"></i> @lang('site.payment'): {{ $invoice->paymentMethod->method_label }}</h3>
            </div>
            <div class="card-body">
                <p>
                    <table class="table table-stripped">
                    <tbody>
                    <tr>
                        <td><strong>@lang('site.item'):</strong></td>
                        <td>{{ $description }}</td>
                    </tr>
                    <tr>
                    <?php
                        $vat = number_format(setting('general_vat'));
                        $amount = number_format($invoice->amount);
                        $tax = number_format($vat * $amount /100, 2);
                        $total = number_format(($tax+$amount), 2);
                    ?>
                        <td><strong>@lang('site.amount'):</strong></td>
                        <td><?php echo price($total); ?></td>
                    </tr>
                    </tbody>
                </table>
                </p>
                <form id="payment-form" class="col-12">
                    <div id="payment-element">
                        <!--Stripe.js injects the Payment Element-->
                    </div>
                    <button id="submit">
                        <div class="spinner hidden" id="spinner"></div>
                        <span id="button-text">Pay Now</span>(<?php echo price($total); ?>)
                    </button>
                    <div id="payment-message" class="hidden"></div>
                </form>
                <!-- @yield('payment-form') -->

            </div>
        </div>
    </div>
</div>

@endsection
@section('footer')
<script src="https://js.stripe.com/v3/"></script>
<script>
  stripe_url = "{{route('user.front.stripe_credit')}}";
  complete_url = "{{route('user.success.payment')}}"
  checkout_post = "{{route('user.front.checkout_post_3')}}"
</script>
<script src="{{ asset('js/stripe.js') }}"></script>
@endsection
