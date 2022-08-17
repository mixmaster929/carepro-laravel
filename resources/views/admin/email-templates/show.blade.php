@extends('layouts.admin-page')

@section('pageTitle',__('site.email-template').': '.$emailtemplate->name)
@section('page-title',__('site.email-template').': '.$emailtemplate->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >


                        @can('access','view_email_templates')
                        <a href="{{ url('/admin/email-templates') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_email_template')
                        <a href="{{ url('/admin/email-templates/' . $emailtemplate->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_email_template')
                        <form method="POST" action="{{ url('admin/email-templates' . '/' . $emailtemplate->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $emailtemplate->id }}</li>
                            <li class="list-group-item active">@lang('site.name')</li>
                            <li class="list-group-item">{{ $emailtemplate->name }}</li>
                            <li class="list-group-item active">@lang('site.subject')</li>
                            <li class="list-group-item">{{ $emailtemplate->subject }}</li>
                            <li class="list-group-item active">@lang('site.message')</li>
                            <li class="list-group-item">{!!   $emailtemplate->message  !!}</li>

                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
