@extends($userLayout)

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
                        <td><strong>@lang('site.amount'):</strong></td>
                        <td>{!! clean( price($invoice->amount,$invoice->currency_id) ) !!}</td>
                    </tr>
                    </tbody>
                </table>
                </p>
                @yield('payment-form')

            </div>
        </div>
    </div>
</div>

@endsection
