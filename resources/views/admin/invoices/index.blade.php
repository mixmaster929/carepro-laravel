@extends('layouts.admin-page-wide')

@section('search-form',url('/admin/invoices'))

@section('pageTitle',__('site.invoices'))

@section('page-title')
    {{ __('site.invoices') }} ({{ $invoices->count() }})

    @if(Request::get('category') && \App\InvoiceCategory::find(request()->category))
        : {{ \App\InvoiceCategory::find(request()->category)->name }}
    @endif

    @if(Request::get('user') && \App\User::find(request()->user))
        : {{ \App\User::find(request()->user)->name }}
    @endif

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
                        @if(!empty($filterParams))
                            <ul  class="list-inline">
                                <li class="list-inline-item" ><strong>@lang('site.filter'):</strong></li>
                                @foreach($filterParams as $param)
                                    @if(null !== request()->get($param)  && request()->get($param) != '')
                                        <li class="list-inline-item" >{{ ucwords(str_ireplace('_',' ',$param)) }}</li>
                                    @endif
                                @endforeach

                            </ul>
                        @endif
                    </div>
                    <div  >

                        <span class="float-right">@lang('site.paid'): {{ price($total) }}</span>
                        @can('access','create_invoice')
                        <a href="{{ url('/admin/invoices/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan

                        <a data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse" class="btn btn-primary btn-sm" title="@lang('site.filter')">
                            <i class="fa fa-filter" aria-hidden="true"></i> @lang('site.filter')
                        </a>

                        <a  href="{{ route('admin.invoices.index') }}" class="btn btn-info btn-sm" title="@lang('site.reset')">
                            <i class="fa fa-sync" aria-hidden="true"></i> @lang('site.reset')
                        </a>

                        <div  class="collapse int_tm20"  id="filterCollapse" >
                            <div  >
                                <form action="{{ route('admin.invoices.index') }}" method="get">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="search" class="control-label">@lang('site.search')</label>
                                                <input class="form-control" type="text" value="{{ request()->search  }}" name="search"/>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group {{ $errors->has('user') ? 'has-error' : ''}}">
                                                <label for="user" class="control-label">@lang('site.user')</label>
                                                <div>
                                                    <select   name="user" id="user" class="form-control">
                                                        <?php
                                                        $userId = request()->user;
                                                        ?>
                                                        @if($userId)
                                                            <option selected value="{{ $userId }}">{{ \App\User::find($userId)->name }} &lt;{{ \App\User::find($userId)->email }}&gt; </option>
                                                        @endif
                                                    </select>
                                                </div>


                                                {!! clean( $errors->first('user', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group  {{ $errors->has('status') ? 'has-error' : ''}}">
                                                <label for="status" class="control-label">@lang('site.status')</label>
                                                <select name="status" class="form-control" id="status" >
                                                    <option></option>
                                                    @foreach (json_decode('{"1":"'.__('site.paid').'","0":"'.__('site.unpaid').'"}', true) as $optionKey => $optionValue)
                                                        <option value="{{ $optionKey }}" {{ ((null !== old('status',@request()->status)) && old('status',@request()->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                                    @endforeach
                                                </select>
                                                {!! clean( $errors->first('status', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                        </div>



                                        <div class="col-md-2">

                                            <div class="form-group">
                                                <label for="min_date" class="control-label">@lang('site.min-date')</label>
                                                <input class="form-control date" type="text" value="{{ request()->min_date  }}" name="min_date"/>
                                            </div>

                                        </div>
                                        <div class="col-md-2">

                                            <div class="form-group">
                                                <label for="max_date" class="control-label">@lang('site.max-date')</label>
                                                <input class="form-control date" type="text" value="{{ request()->max_date  }}" name="max_date"/>
                                            </div>

                                        </div>




                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="category" class="control-label">@lang('site.invoice-category')</label>
                                                <select  class="form-control" name="category" id="category">
                                                    <option value=""></option>
                                                    @foreach(\App\InvoiceCategory::get() as $invoiceCategory)
                                                        <option @if(request()->category==$invoiceCategory->id) selected @endif value="{{ $invoiceCategory->id }}">{{ $invoiceCategory->name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-block">@lang('site.filter')</button>
                                    </div>

                                </form>
                            </div>
                        </div>


                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table break">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th >@lang('site.user')</th>
                                        <th>@lang('site.item')</th>
                                        <th>@lang('site.amount')</th>
                                        <th>@lang('site.status')</th>
                                        <th>@lang('site.created-on')</th>
                                        <th>@lang('site.due-date')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($invoices as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td  >

                                            <a @if($item->user->role_id==2) href="{{ url('/admin/employers/' . $item->user_id) }}" @elseif($item->user->role_id==3)  href="{{ url('/admin/candidates/' . $item->user_id) }}" @endif >{{ $item->user->name }} ({{ roleName($item->user->role_id) }})</a>


                                        </td>
                                        <td  >{{ $item->title }} </td>
                                        <td>{!! clean( check( price($item->amount)) ) !!}</td>
                                        <td>{{ ($item->paid==1)? __('site.paid'):__('site.unpaid') }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/M/Y') }}
                                        </td>
                                        <td>
                                            @if(!empty($item->due_date))
                                            {{ \Carbon\Carbon::parse($item->due_date)->format('d/M/Y') }}
                                                @endif
                                        </td>
                                        <td>


                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">


                                                    @can('access','approve_invoice')
                                                    @if($item->paid==0)
                                                        <a class="dropdown-item"  onclick="return confirm('@lang('site.confirm-approve')')"  href="{{ route('admin.invoices.approve',['invoice'=>$item->id]) }}"><i class="fa fa-thumbs-up"></i> @lang('site.approve')</a>
                                                        @endif
                                                        @endcan



                                                    <!-- Dropdown menu links -->
                                                    @can('access','view_invoice')
                                                    <a class="dropdown-item" href="{{ url('/admin/invoices/' . $item->id) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_invoice')
                                                    <a class="dropdown-item" href="{{ url('/admin/invoices/' . $item->id . '/edit') }}"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                                    @endcan




                                                    @can('access','delete_invoice')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()"><i class="fa fa-trash"></i> @lang('site.delete')</a>
                                                    @endcan



                                                </div>
                                            </div>


                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/invoices' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean(  $invoices->appends(request()->input())->links() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/order-search.js') }}"></script>
    <script  type="text/javascript">
"use strict";


        $('#user').select2({
            placeholder: "@lang('site.search-users')...",
            minimumInputLength: 3,
            ajax: {
                url: '{{ route('admin.users.search') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }

        });

    </script>
@endsection


@section('header')
    @parent
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link href="{{ asset('vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.time.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.css') }}" rel="stylesheet">


@endsection
