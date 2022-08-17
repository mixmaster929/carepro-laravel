@extends($userLayout)

@section('page-title',$title)

@section('content')


    @section('category-select')
    <div class="row int_mb30px"  >
        <div class="col-md-4 offset-8">
            <form name="categoryForm" id="categoryForm" action="{{ route('vacancies') }}" method="get">
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
            <th>@lang('site.category')</th>
            <th>@lang('site.location')</th>
            <th>@lang('site.salary')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            @foreach($vacancies as $vacancy)
                <tr>
                    <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                    <td>{{ $vacancy->title }}</td>
                    <td>
                        <ul class="csv">
                            @foreach($vacancy->jobCategories as $category)
                                    <li>{{ $category->name }}</li>
                                @endforeach
                        </ul>
                    </td>
                    <td>{{ $vacancy->location }}</td>
                    <td>{{ $vacancy->salary }}</td>
                    <td><a class="btn btn-primary rounded" href="{{ route('view-vacancy',['vacancy'=>$vacancy->id]) }}">@lang('site.details')</a></td>
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
