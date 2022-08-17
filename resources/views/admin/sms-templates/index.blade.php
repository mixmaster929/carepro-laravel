@extends('layouts.admin-page')


@section('pageTitle',__('site.sms-templates'))
@section('page-title',__('site.sms-templates'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        @can('access','create_sms_template')
                        <a href="{{ url('/admin/sms-templates/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('site.name')</th><th>@lang('site.message')</th><th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($smstemplates as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td>{{ $item->name }}</td><td>{{ limitLength($item->message,80) }}</td>
                                        <td>

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->

                                                    @can('access','view_sms_template')
                                                    <a class="dropdown-item" href="{{ url('/admin/sms-templates/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_sms_template')
                                                    <a class="dropdown-item" href="{{ url('/admin/sms-templates/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan

                                                    @can('access','delete_sms_template')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan



                                                </div>
                                            </div>

                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/sms-templates' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $smstemplates->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
