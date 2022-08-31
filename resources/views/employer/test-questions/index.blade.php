@extends($userLayout)

@section('search-form',url('/employer/test-questions'))

@section('pageTitle',__('site.questions').': '.$test->name)
@section('page-title',__('site.questions').': '.$test->name)

@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=> route('employer.tests.index'),
                'page'=>__('site.tests')
            ],
            [
                'link'=>'#',
                'page'=>__('site.manage-questions')
            ]
    ]])
@endsection

@section('content')
    <div>
        <a href="{{ url('/employer/tests') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
        <a href="{{ route('employer.test-questions.create',['test'=>$test->id]) }}" class="btn btn-success btn-sm">
            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
        </a>
        <br/>
        <br/>
        <div class="table">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('site.question')</th>
                        <th>@lang('site.options')</th>
                        <th>@lang('site.sort-order')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($testquestions as $item)
                    <tr>
                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                        <td>{!! clean( $item->question ) !!}</td>
                        <td>{{ $item->testOptions()->count() }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                            <div class="btn-group dropup">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                </button>
                                <div class="dropdown-menu">
                                    <!-- Dropdown menu links -->
                                    <a class="dropdown-item" href="{{ url('/employer/test-questions/' . $item->id) }}">@lang('site.view')</a>
                                    <a class="dropdown-item" href="{{ url('/employer/test-questions/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                </div>
                            </div>
                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/employer/test-questions' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! clean( $testquestions->appends(request()->input())->render() ) !!} </div>
        </div>

    </div>
@endsection
