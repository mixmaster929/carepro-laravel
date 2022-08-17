@extends('layouts.admin-page')

@section('pageTitle',__('site.email-resource').': '.$emailresource->name)
@section('page-title',__('site.email-resource').': '.$emailresource->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >


                        <form method="POST" action="{{ url('admin/email-resources' . '/' . $emailresource->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        <br/>
                        <br/>


                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $emailresource->id }}</li>
                            <li class="list-group-item active">@lang('site.name')</li>
                            <li class="list-group-item">{{ $emailresource->name }}</li>
                            <li class="list-group-item active">@lang('site.file')</li>
                            <li class="list-group-item">{{ $emailresource->file }}</li>

                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
