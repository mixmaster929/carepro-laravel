@extends('layouts.admin-page')

@section('pageTitle',__('site.role').': '.$role->name)
@section('page-title',__('site.role').': '.$role->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ url('/admin/roles') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ url('/admin/roles/' . $role->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>

                        <form method="POST" action="{{ url('admin/roles' . '/' . $role->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr><th> @lang('site.name') </th><td> {{ $role->name }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                        <h1>@lang('site.permissions')</h1>

                        @foreach(\App\PermissionGroup::orderBy('sort_order')->get() as $group)
                            @if($role->permissions()->where('permission_group_id',$group->id)->count()>0)
                            <div class="card">
                                <div class="card-header">
                                    @lang('perm.'.$group->name)
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($group->permissions as $permission)
                                            @if($role->permissions()->find($permission->id))
                                        <li class="list-group-item">@lang('perm.'.$permission->name)</li>
                                            @endif
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            @endif
                      @endforeach



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
