@extends('layouts.admin-page-wide')

@section('search-form',route('admin.users.index'))

@section('pageTitle',__('site.manage-users'))

@section('page-title')
    @lang('site.all-users') (@lang('site.total-used'): {{ $total }} @if(saas()) / @lang('site.limit'): {{ USER_LIMIT }}@endif)
    @if(Request::get('search'))
        : {{ Request::get('search') }}
    @endif
@endsection
@section('page-content')
    @can('access','manage_settings')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >


                    <div>




                        <a data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse" class="btn btn-primary btn-sm" title="@lang('site.filter')">
                            <i class="fa fa-filter" aria-hidden="true"></i> @lang('site.filter')
                        </a>

                        <a  href="{{ route('admin.users.index') }}" class="btn btn-info btn-sm" title="@lang('site.filter')">
                            <i class="fa fa-sync" aria-hidden="true"></i> @lang('site.reset')
                        </a>


                        <div class="collapse int_tm20" id="filterCollapse" >
                            <div  >
                                <form action="{{ route('admin.users.index') }}" method="get">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="search" class="control-label">@lang('site.search')</label>
                                                <input class="form-control" type="text" value="{{ request()->search  }}" name="search"/>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group  {{ $errors->has('role') ? 'has-error' : ''}}">
                                                <label for="role" class="control-label">@lang('site.type')</label>
                                                <select name="role" class="form-control" id="role" >
                                                    <option></option>
                                                    @foreach (json_decode('{"1":"'.__('site.administrator').'","2":"'.__('site.employer').'","3":"'.__('site.candidate').'"}', true) as $optionKey => $optionValue)
                                                        <option value="{{ $optionKey }}" {{ ((null !== old('role',@request()->role)) && old('role',@request()->role) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                                    @endforeach
                                                </select>
                                                {!! clean( $errors->first('role', '<p class="help-block">:message</p>') ) !!}
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
                                    <th>@lang('site.name')</th>
                                    <th>@lang('site.email')</th>
                                    <th>@lang('site.type')</th>
                                    <th>@lang('site.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>

                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ roleName($item->role_id) }}</td>
                                        <td>

                                            <a class="btn btn-sm btn-primary" href="{{ userLink($item) }}" target="_blank"><i class="fa fa-eye"></i> @lang('site.view')</a>
                                            <a class="btn btn-sm btn-danger" href="{{ route('admin.users.delete',['user'=>$item->id]) }}" onclick="return confirm('@lang('site.confirm-delete')')"><i class="fa fa-trash"></i> @lang('site.delete')</a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $users->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <h2>@lang('site.no-users-permission')</h2>
    @endcan






@endsection
