@extends('layouts.admin-page')

@section('search-form',url('/admin/tests'))

@section('pageTitle',__('site.tests'))

@section('page-title')
    {{ __('site.tests') }} ({{ $tests->count() }})
    @if(Request::get('search'))
        : {{ Request::get('search') }}
    @endif
@endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div>
                        @can('access','create_test')
                        <a href="{{ url('/admin/tests/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('site.name')</th>
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
                                                    @can('access','view_test_questions')
                                                    <a class="dropdown-item" href="{{ route('admin.test-questions.index',['test'=>$item->id]) }}">@lang('site.manage-questions')</a>
                                                    @endcan

                                                    @can('access','view_test_results')
                                                    <a class="dropdown-item" href="{{ route('admin.tests.attempts',['test'=>$item->id]) }}">@lang('site.view-attempts')</a>
                                                    @endcan

                                                    @can('access','view_test')
                                                    <a class="dropdown-item" href="{{ url('/admin/tests/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan


                                                    @can('access','edit_test')
                                                    <a class="dropdown-item" href="{{ url('/admin/tests/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan


                                                    @can('access','delete_test')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan




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
                </div>
            </div>
        </div>
    </div>
@endsection
