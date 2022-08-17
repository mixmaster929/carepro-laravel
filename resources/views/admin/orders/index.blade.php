@extends('layouts.admin-page-wide')

@section('search-form',url('/admin/orders'))

@section('pageTitle',__('site.orders'))
@section('page-title')
    {{ $title }} ({{ $orders->count() }})
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

                        @can('access','create_order')
                        <a id="ocreatebtn" data-toggle="modal" data-target="#exampleModal" href="#" class="btn btn-success btn-sm" >
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>


                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">@lang('amenu.create-order')</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form class="form" action="@route('admin.orders.do-create')" method="post">
                                    <div class="modal-body">

                                            @csrf

                                            <div class="form-group">
                                                <label for="form">@lang('site.form')</label>
                                                <select required class="form-control" name="form" >
                                                    <option ></option>
                                                    @foreach(\App\OrderForm::get() as $form)

                                                        <option @if(request()->form == $form->id) selected @endif value="{{ $form->id }}">{{ $form->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">@lang('site.proceed')</button>
                                    </div>
                                    </form>

                                </div>
                            </div>
                        </div>



                        @endcan

                        <a data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse" class="btn btn-primary btn-sm" title="@lang('site.filter')">
                            <i class="fa fa-filter" aria-hidden="true"></i> @lang('site.filter')
                        </a>

                        <a  href="{{ route('admin.orders.index') }}" class="btn btn-info btn-sm" title="@lang('site.reset')">
                            <i class="fa fa-sync" aria-hidden="true"></i> @lang('site.reset')
                        </a>

                        <div  class="collapse int_tm20"  id="filterCollapse" >
                            <div  >
                                <form action="{{ route('admin.orders.index') }}" method="get">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="search" class="control-label">@lang('site.search')</label>
                                                <input class="form-control" type="text" value="{{ request()->search  }}" name="search"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="form">@lang('site.form')</label>
                                                <select class="form-control" name="form" id="form">
                                                    <option ></option>
                                                    @foreach(\App\OrderForm::get() as $form)

                                                        <option @if(request()->form == $form->id) selected @endif value="{{ $form->id }}">{{ $form->name }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group  {{ $errors->has('status') ? 'has-error' : ''}}">
                                                <label for="status" class="control-label">@lang('site.status')</label>
                                                <select name="status" class="form-control" id="status" >
                                                    <option></option>
                                                    @foreach (json_decode('{"p":"'.__('site.pending').'","i":"'.__('site.in-progress').'","c":"'.__('site.completed').'","x":"'.__('site.cancelled').'"}', true) as $optionKey => $optionValue)
                                                        <option value="{{ $optionKey }}" {{ ((null !== old('status',@request()->status)) && old('status',@request()->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                                    @endforeach
                                                </select>
                                                {!! clean( $errors->first('status', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="min_date" class="control-label">@lang('site.min-date')</label>
                                                <input class="form-control date" type="text" value="{{ request()->min_date  }}" name="min_date"/>
                                            </div>

                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="max_date" class="control-label">@lang('site.max-date')</label>
                                                <input class="form-control date" type="text" value="{{ request()->max_date  }}" name="max_date"/>
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.employer')</th>
                                        <th>@lang('site.added-on')</th>
                                        <th>@lang('site.form')</th>
                                        <th>@lang('site.status')</th>
                                        <th>@lang('site.shortlist')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
                                        <td>
                                  @if($item->orderForm()->exists())

                                      {{limitLength($item->orderForm->name,50)}}
                                      @endif
                                        </td>
                                        <td>
                                            {{ orderStatus($item->status) }}
                                        </td>
                                        <td>
                                            {{ $item->candidates()->count() }}
                                        </td>
                                        <td>

                                            @can('access','view_order_comments')
                                            <a href="{{ route('admin.order-comments.index',['order'=>$item->id]) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-comments" aria-hidden="true"></i> @lang('site.comments')({{ $item->orderComments()->count() }})</button></a>
                                            @endcan

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->
                                                    @can('access','view_order')
                                                    <a class="dropdown-item" href="{{ url('/admin/orders/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_order')
                                                    <a class="dropdown-item" href="{{ url('/admin/orders/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan


                                                    @can('access','create_employment')
                                                    <a class="dropdown-item" href="{{ route('admin.employments.create',['user'=>$item->user_id]) }}">@lang('site.create-employment')</a>
                                                    @endcan

                                                    @can('access','delete_order')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan



                                                </div>
                                            </div>

                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/orders' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $orders->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/admin/pickadate.js') }}" type="text/javascript"></script>
    @if(request()->has('create'))
        <script src="{{ asset('js/admin/ocreate.js') }}" type="text/javascript"></script>

    @endif
@endsection


@section('header')
    @parent
    <link href="{{ asset('vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.time.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.css') }}" rel="stylesheet">


@endsection
