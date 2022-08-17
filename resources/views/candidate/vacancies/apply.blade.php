@extends($userLayout)

@section('page-title',__('site.apply').': '.$vacancy->title)

@section('content')


    @section('breadcrumb')
                        <li class="breadcrumb-item"><a href="{{ route('vacancies') }}">@lang('site.vacancies')</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('view-vacancy',['vacancy'=>$vacancy->id]) }}">@lang('site.vacancy-details')</a></li>

                        <li class="breadcrumb-item active" aria-current="page">@lang('site.apply')</li>


    @endsection

<div class="card">
    <div class="card-body">
        <h5 class="card-title">@lang('site.upload-cv')</h5>

        <p class="card-text">@lang('site.upload-cv-text')</p>

        <form action="{{ route('candidate.vacancy.submit',['vacancy'=>$vacancy->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="cv">@lang('site.cv-resume')</label>
                <input name="cv" class="form-control" type="file" required />
            </div>
            <button type="submit" class="btn btn-primary  btn-block rounded">@lang('site.apply-now')</button>
        </form>


     </div>
</div>


@endsection

