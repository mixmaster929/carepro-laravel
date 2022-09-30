@extends($userLayout)

@section('page-title')
	{{ __('site.all-vacancies') }}
@endsection

@section('content')

    @section('category-select')
    <div class="row int_mb30px"  >
        <div class="col-md-4 offset-4">
            <form name="regionForm" id="regionForm" action="{{ route('employer.allvacancies') }}" method="get">
                <select class="form-control" name="region" id="region" onchange="$('#regionForm').submit()">
                    <option value="">@lang('site.all-regions')</option>
                    @foreach($regions as $region)
                        <option @if(request('region')==$region->id) selected @endif value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    
        <div class="col-md-4">
            <form name="categoryForm" id="categoryForm" action="{{ route('employer.allvacancies') }}" method="get">
                <select class="form-control" name="category" id="category" onchange="$('#categoryForm').submit()">
                    <option value="">@lang('site.all-vacancies')</option>
                    @foreach($categories as $category)
                        <option @if(request('category')==$category->id) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    @show

<div>

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('site.position')</th>
            <th>@lang('site.region')</th>
            <th>@lang('site.category')</th>
            <th>@lang('site.location')</th>
            <th>@lang('site.salary')</th>
        </tr>
        </thead>
        <tbody>
            @foreach($vacancies as $vacancy)
                <tr>
                    <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                    <td>{{ $vacancy->title }}</td>
                    <td>
                    @if(request()->region)
                        @foreach($vacancy->jobRegions as $jobRegion)
                            @if($jobRegion->id == request()->region)
                                {{ $jobRegion->name }}
                            @endif
                        @endforeach
                    @else
                    {{ $vacancy->getRegion()? $vacancy->getRegion()->name : null }}
                    @endif
                    </td>
                    <td>
                    @if(request()->category)
                        @foreach($vacancy->jobCategories as $category)
                            @if($category->id == request()->category)
                                {{ $category->name }}
                            @endif
                        @endforeach
                    @else
                    {{ $vacancy->getCategory()? $vacancy->getCategory()->name : null }}
                    @endif
                    </td>
                    <td>{{ $vacancy->location }}</td>
                    <td>{{ $vacancy->salary }}</td>
					<td><a class="btn btn-primary rounded" href="{{ route('employer.view-vacancy',['vacancy'=>$vacancy->id]) }}">@lang('site.details')</a></td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <div>
        {{ $vacancies->links() }}
    </div>





</div>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/vacancies.css') }}">

@endsection
