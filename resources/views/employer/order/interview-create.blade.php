@extends($userLayout)

@section('pageTitle',__('site.create-new').' '.__('site.interview'))
@section('page-title',__('site.create-new').' '.__('site.interview'))

@section('content')
    <div>
        <a href="{{ url('employer/offers/'.$order->id) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
        <br />
        <br />
        <form method="POST" action="{{ url('employer/offer/store-interview') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include ('employer.order.interview-form')
        </form>
    </div>
@endsection
@include('employer.interview.search-script')