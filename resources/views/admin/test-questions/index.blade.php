@extends('layouts.admin-page')

@section('search-form',url('/admin/test-questions'))

@section('pageTitle',__('site.questions').': '.$test->name)
@section('page-title',__('site.questions').': '.$test->name)

@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=> route('admin.tests.index'),
                'page'=>__('site.tests')
            ],
            [
                'link'=>'#',
                'page'=>__('site.manage-questions')
            ]
    ]])
@endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        @can('access','view_tests')
                        <a href="{{ url('/admin/tests') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        <a href="{{ route('admin.test-questions.create',['test'=>$test->id]) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>



                        <br/>
                        <br/>
                        <div class="table-responsive">
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

                                                    @can('access','view_test_question')
                                                    <a class="dropdown-item" href="{{ url('/admin/test-questions/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan


                                                    @can('access','edit_test_question')
                                                    <a class="dropdown-item" href="{{ url('/admin/test-questions/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan


                                                    @can('access','delete_test_question')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan




                                                </div>
                                            </div>

                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/test-questions' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
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
                </div>
            </div>
        </div>
    </div>
@endsection
