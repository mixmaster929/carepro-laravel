@extends('layouts.admin-page')

@section('pageTitle',__('site.administrator').': '.$admin->name)
@section('page-title',__('site.administrator').': '.$admin->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ url('/admin/admins') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ url('/admin/admins/' . $admin->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>

                        <form method="POST" action="{{ url('admin/admins' . '/' . $admin->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $admin->id }}</li>
                            <li class="list-group-item active">@lang('site.name')</li>
                            <li class="list-group-item">{{ $admin->name }}</li>
                            <li class="list-group-item active">@lang('site.email')</li>
                            <li class="list-group-item">{{ $admin->email }}</li>
                            <li class="list-group-item active">@lang('site.enabled')</li>
                            <li class="list-group-item">{{ boolToString($admin->enabled) }}</li>

                            <li class="list-group-item active">@lang('site.roles')</li>
                            <li class="list-group-item">

                                <ul class="csv">
                                    @foreach($admin->adminRoles as $role)
                                        <li>{{ $role->name }}</li>
                                    @endforeach
                                </ul>
                            </li>



                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
