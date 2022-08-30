@extends($userLayout)
@section('page-title',__('site.interview'))
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('candidate.interviews') }}">@lang('site.interviews')</a></li>
    <li class="breadcrumb-item">@lang('site.view')</li>
@endsection

@section('content')
    <div class="card bd-0 int_mb30px"  >
        <div class="card-header tx-medium bd-0 tx-white bg-indigo">
            {{ \Illuminate\Support\Carbon::parse($interview->interview_date)->format('d/M/Y') }} ({{ \Illuminate\Support\Carbon::parse($interview->interview_date)->diffForHumans() }})
        </div><!-- card-header -->
        <div class="card-body bd bd-t-0">
            <p class="mg-b-0">
            <div class="card bd-0">
                <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
                    <nav class="nav nav-tabs">
                        <a class="nav-link active" data-toggle="tab" href="#tabCont1{{ $interview->id }}">@lang('site.details')</a>
                        @if($interview->feedback==1 && \Illuminate\Support\Carbon::parse($interview->interview_date) < \Illuminate\Support\Carbon::now() )
                            <a class="nav-link" data-toggle="tab" href="#tabCont3{{ $interview->id }}">@lang('site.feedback')</a>
                        @endif
                    </nav>
                </div><!-- card-header -->
                <div class="card-body bd bd-t-0 tab-content">
                    <div id="tabCont1{{ $interview->id }}" class="tab-pane active show">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>@lang('site.interview-date')</label>
                                <div>{{ \Illuminate\Support\Carbon::parse($interview->interview_date)->format('d/M/Y') }}</div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('site.interview-time')</label>
                                <div>{{ $interview->interview_time }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>@lang('site.comment')</label>
                                <div>{!! clean( nl2br(clean($interview->employer_comment)) ) !!}</div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('site.venue')</label>
                                <div>{!! clean( nl2br(clean($interview->venue)) ) !!}</div>
                            </div>
                        </div>
                    </div><!-- tab-pane -->
                </div><!-- card-body -->
            </div><!-- card -->
            </p>
        </div><!-- card-body -->
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/interview.css') }}">
@endsection
