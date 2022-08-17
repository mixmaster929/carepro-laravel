@extends('layouts.admin-page')

@section('pageTitle',__('site.text-message').' #'.$sm->id)
@section('page-title',__('site.text-message').' #'.$sm->id)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        @can('access','view_text_messages')
                        <a href="{{ url('/admin/sms') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                       @endcan

                        @can('access','edit_text_message')
                        <a href="{{ url('/admin/sms/' . $sm->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_text_message')
                        <form method="POST" action="{{ url('admin/sms' . '/' . $sm->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan
                        <br/>
                        <br/>


                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home">@lang('site.details')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu1">@lang('site.users')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu2">@lang('site.categories')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu3">@lang('site.recipients')</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane container active" id="home">
                                <br/>


                                <ul class="list-group">
                                    <li class="list-group-item active">@lang('site.id')</li>
                                    <li class="list-group-item">{{ $sm->id }}</li>
                                    <li class="list-group-item active">@lang('site.message')</li>
                                    <li class="list-group-item">{!! clean( nl2br(clean($sm->message)) ) !!}</li>
                                    <li class="list-group-item active">@lang('site.comment')</li>
                                    <li class="list-group-item">{{ $sm->comment }}</li>
                                    <li class="list-group-item active">@lang('site.send-date')</li>
                                    <li class="list-group-item">{{ \Illuminate\Support\Carbon::parse($sm->send_date)->toDateString() }}</li>
                                    <li class="list-group-item active">@lang('site.gateway')</li>
                                    <li class="list-group-item">{{ $sm->smsGateway->gateway_name }}</li>
                                    <li class="list-group-item active">@lang('site.sent')</li>
                                    <li class="list-group-item">{{ boolToString($sm->sent) }}</li>
                                    <li class="list-group-item active">@lang('site.recipients')</li>
                                    <li class="list-group-item">{{ $sm->users()->count() }}</li>

                                </ul>



                            </div>
                            <div class="tab-pane container fade" id="menu1">

                                <br/>
                                <table class="table">
                                    <thead>
                                   <tr>
                                       <th>#</th>
                                       <th>@lang('site.name')</th>
                                       <th>@lang('site.telephone')</th>
                                       <th></th>
                                   </tr>
                                    </thead>

                                    <tbody>
                                            @foreach($sm->users as $user)

                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $user->name }} ({{ roleName($user->role_id) }})</td>
                                                    <td>{{ $user->telephone }}</td>
                                                    <td><a target="_blank" class="btn btn-sm btn-primary" href="{{ userLink($user) }}">@lang('site.view')</a></td>
                                                </tr>

                                                @endforeach
                                    </tbody>

                                </table>



                            </div>
                            <div class="tab-pane container fade" id="menu2">

                                <br/>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.name')</th>
                                        <th>@lang('site.candidates')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($sm->categories as $category)

                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->candidates()->count() }}</td>
                                        </tr>

                                    @endforeach
                                    </tbody>

                                </table>


                            </div>
                            <div class="tab-pane container fade" id="menu3">

                                <br/>
                                <table class="table" id="recipients">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.telephone')</th>
                                        <th>@lang('site.user')</th>

                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($sm->smsRecipients()->limit(500)->get() as $recipient)

                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $recipient->telephone }}</td>
                                            <td>
                                            @if(phoneUser($recipient->telephone))
                                                {{ phoneUser($recipient->telephone)->name }} ({{ roleName(phoneUser($recipient->telephone)->role_id) }})
                                            @endif
                                            </td>

                                            <td>
                                                @if(phoneUser($recipient->telephone))
                                                <a target="_blank" class="btn btn-sm btn-primary" href="{{ userLink(phoneUser($recipient->telephone)) }}">@lang('site.view')</a>
                                                @endif
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>

                                </table>

                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/media/css/jquery.dataTables.min.css') }}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script>
"use strict";
        $(function(){
            $('#recipients').DataTable({
                language: {
                    search: "@lang('site.search'):",
                    info: "@lang('site.table-info')",
                    emptyTable: "@lang('site.empty-table')",
                    lengthMenu:    "@lang('site.table-length')",
                    paginate: {
                        first:      "@lang('site.first')",
                        previous:   "@lang('site.previous')",
                        next:       "@lang('site.next')",
                        last:       "@lang('site.last')"
                    }
                }
            });
        });
    </script>
@endsection
