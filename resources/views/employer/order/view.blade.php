@extends($userLayout)

@section('page-title',__('site.order').' #'.$order->id)
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('employer.orders') }}">@lang('site.orders')</a></li>
    <li class="breadcrumb-item">@lang('site.view')</li>
@endsection

@section('content')
    <div class="card bd-0">
        <div class="card-body bd bd-t-0 tab-content">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6 {{ $errors->has('user_id') ? 'has-error' : ''}}">
                        <label for="user_id" class="control-label">@lang('site.id')</label>
                        <div>{{ $order->id }}</div>
                    </div>
                    <div class="form-group col-md-6  ">
                        <label for="added" class="control-label">@lang('site.added-on')</label>
                        <div>{{ \Illuminate\Support\Carbon::parse($order->created_at)->format('d/M/Y') }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6 {{ $errors->has('status') ? 'has-error' : ''}}">
                        <label for="status" class="control-label">@lang('site.status')</label>
                        <div>{{ orderStatus($order->status) }}</div>
                    </div>
                    <div class="form-group col-md-6 {{ $errors->has('interview_date') ? 'has-error' : ''}}">
                        <label for="interview_date" class="control-label">@lang('site.interview-date')</label>
                        @if(!empty($order->interview_date))
                            <div>{{ \Illuminate\Support\Carbon::parse($order->interview_date)->format('d/M/Y') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>@lang('site.interview-location')</label>
                        <div>{!! clean( nl2br(clean($order->interview_location)) ) !!}</div>
                    </div>
                    <div class="form-group col-md-6">
                        <label>@lang('site.interview-time')</label>
                        <div>{{ $order->interview_time }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>@lang('site.comments')</label>
                        <div>{!! clean( nl2br(clean($order->comments)) ) !!}</div>
                    </div>
                </div>
            </div>
        </div><!-- accordion -->
    </div><!-- card -->

@endsection
@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/boldheader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/boldlabel.css') }}">
    @endsection

@section('footer')
@endsection
