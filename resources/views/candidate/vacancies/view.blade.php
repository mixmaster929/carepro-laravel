@extends($userLayout)

@section('page-title',__('site.vacancy').': '.$vacancy->title)

@section('content')



    @section('breadcrumb')

                        <li class="breadcrumb-item"><a href="{{ route('vacancies') }}">@lang('site.vacancies')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('site.vacancy-details')</li>


        @endsection
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $vacancy->title }}</h5>
            @if(!empty($vacancy->closes_at))
                <h6 class="card-subtitle mb-2 text-muted">@lang('site.closes-on') {{ \Illuminate\Support\Carbon::parse($vacancy->closes_at)->format('d/M/Y') }}</h6>
            @endif

            <p class="card-text">{!! clean( check($vacancy->description) ) !!}</p>

            <p class="card-text">@lang('site.location')<hr>{{ $vacancy->location }}</p>
            <p class="card-text">@lang('site.region')<br>
                <ul class="csv">
                    @foreach($vacancy->jobRegions as $region)
                        <li>{{ $region->name }}</li>
                    @endforeach
                </ul>
            </p><hr>
            <p class="card-text">@lang('site.salary')<hr>{{ $vacancy->salary }}</p>
            <p class="card-text">@lang('site.category')<br>
                <ul class="csv">
                    @foreach($vacancy->jobCategories as $category)
                        <li>{{ $category->name }}</li>
                    @endforeach
                </ul>
            </p><hr>
            <a onclick="return confirm('@lang('site.confirm-application')')" href="{{ route('candidate.vacancy.apply',['vacancy'=>$vacancy->id]) }}" class="card-link btn btn-primary btn-block">@lang('site.apply')</a>
        </div>
    </div>

    @endsection
