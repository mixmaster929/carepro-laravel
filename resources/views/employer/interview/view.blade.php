@extends($userLayout)
@section('page-title',__('site.interview'))
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('employer.interviews') }}">@lang('site.interviews')</a></li>
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
                        <a class="nav-link" data-toggle="tab" href="#tabCont2{{ $interview->id }}">@lang('site.candidates') ({{ $interview->candidates()->count() }})</a>
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
                    <div id="tabCont2{{ $interview->id }}" class="tab-pane">


                        <div class="row">
                            @foreach($interview->candidates as $item)

                                <div class="card col-md-3 bd-0 rounded">

                                    @if(!empty($item->picture) && file_exists($item->picture))
                                        <img  class="img-fluid"   src="{{ asset($item->picture) }}">
                                    @elseif($item->gender=='m')
                                        <img  class="img-fluid"   src="{{ asset('img/man.jpg') }}">
                                    @else
                                        <img  class="img-fluid"   src="{{ asset('img/woman.jpg') }}">
                                    @endif
                                    <div class="card-body bd bd-t-0">
                                        <h5 class="card-title">{{ $item->display_name }}</h5>
                                        <p class="card-text">
                                            <strong>@lang('site.age'):</strong> {{ getAge(\Illuminate\Support\Carbon::parse($item->date_of_birth)->timestamp) }}
                                            <br/>
                                            <strong>@lang('site.gender'):</strong> {{ gender($item->gender) }}
                                        </p>
                                        <a target="_blank" href="{{ route('profile',['candidate'=>$item->id]) }}" class="card-link  btn btn-sm btn-primary rounded">@lang('site.view-profile')</a>
                                    </div>
                                </div><!-- card -->


                            @endforeach
                        </div>


                    </div>
                    @if($interview->feedback==1 && \Illuminate\Support\Carbon::parse($interview->interview_date) < \Illuminate\Support\Carbon::now() )

                        <div id="tabCont3{{ $interview->id }}" class="tab-pane">
                            @if(!empty($interview->employer_feedback))
                                <h5>@lang('site.your-feedback')</h5>
                                <p>
                                    {{ $interview->employer_feedback }}
                                </p>
                                <hr/>
                            @endif

                                <form action="{{ route('employer.update-interview',['interview'=>$interview->id]) }}" method="post">
                                  @csrf
                                    <div class="form-group">
                                        <label for="employer_feedback">@lang('site.feedback-label')</label>
                                        <textarea class="form-control" name="employer_feedback" id="employer_feedback" >{{ old('employer_feedback') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">@lang('site.save')</button>
                                </form>

                        </div>
                    @endif
                </div><!-- card-body -->
            </div><!-- card -->
            </p>
        </div><!-- card-body -->
    </div>


@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/interview.css') }}">


@endsection
