@extends($userLayout)
@section('pageTitle',__('site.select-address'))
@section('page-title',__('site.select-address'))
    @section('content')
        <a href="{{ route('user.invoice.checkout')  }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
        <br />
        <br />


        <div class="row">
            <?php $count=1; ?>
        @foreach($addresses as $address)
            <div class="col-md-4 int_ffminheight">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">@lang('site.address') {{ $count }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>@lang('site.name'):</strong> {{ $address->name }}</p>
                                <p><strong>@lang('site.street-address'):</strong> {{ $address->address }}</p>
                                @if(!empty($address->address2))
                                    <p><strong>@lang('site.street-address-2'):</strong> {{ $address->address2 }}</p>
                                @endif
                                <p><strong>@lang('site.city'):</strong> {{ $address->city }}</p>
                                <p><strong>@lang('site.state'):</strong> {{ $address->state }}</p>
                                <p><strong>@lang('site.zip'):</strong> {{ $address->zip }}</p>
                                <p><strong>@lang('site.country'):</strong> {{ $address->country->name }}</p>
                                <a class="btn btn-primary" href="{{ route('user.invoice.set-address',['id'=>$address->id]) }}">@lang('site.select')</a>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <?php $count++; ?>
        @endforeach
        </div>
        @endsection
