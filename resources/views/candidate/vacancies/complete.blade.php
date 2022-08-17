@extends($userLayout)

@section('page-title',__('site.application-complete'))

@section('content')

@section('breadcrumb')

                    <li class="breadcrumb-item"><a href="{{ route('vacancies') }}">@lang('site.vacancies')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('site.application-complete')</li>


@endsection
<p>
@lang('site.application-complete-msg')
</p>
@endsection
