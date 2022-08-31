@extends($userLayout)

@section('pageTitle',__('site.tests'))

@section('page-title')
    {{ __('site.tests') }} ({{ $tests->count() }})
    @if(Request::get('search'))
        : {{ Request::get('search') }}
    @endif
@endsection

@section('content')
    <div>
        <a href="{{ url('/employer/tests/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
        </a>
        <br/>
        <br/>
        <div class="table">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('site.name')</th>
                        <th>@lang('site.creator')</th>
                        <th>@lang('site.status')</th>
                        <th>@lang('site.questions')</th>
                        <th>@lang('site.attempts')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tests as $item)
                    <tr>
                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->user->name}}</td>
                        <td>{{ empty($item->status)? __('site.disabled'):__('site.enabled') }}</td>
                        <td>{{ $item->testQuestions()->count() }}</td>
                        <td>{{ $item->userTests()->count() }}</td>
                        <td>
                            <div class="btn-group dropup">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                </button>
                                <div class="dropdown-menu">
                                    <!-- Dropdown menu links -->
                                    <a class="dropdown-item" href="{{ route('employer.test-questions.index',['test'=>$item->id]) }}">@lang('site.manage-questions')</a>
                                    <a class="dropdown-item" href="{{ route('employer.tests.attempts',['test'=>$item->id]) }}">@lang('site.view-attempts')</a>
                                    <a class="dropdown-item" href="{{ url('/employer/tests/' . $item->id) }}">@lang('site.view')</a>
                                    <a class="dropdown-item" href="{{ url('/employer/tests/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                </div>
                            </div>

                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/tests' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! clean( $tests->appends(request()->input())->render() ) !!} </div>
        </div>
    </div>
@endsection
