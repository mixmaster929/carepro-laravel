@extends('layouts.admin-page')

@section('search-form',url('/admin/contracts'))
@section('search-form-extras')
    @if(request()->has('user_id'))
        <input type="hidden" name="user_id" value="{{ request()->user_id }}">
    @endif
@endsection
@section('pageTitle',$title)
@section('page-title',$title)

@section('page-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <div  >
                        <a href="{{ url('/admin/contracts/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-contract')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table  table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.name')</th>
                                        <th>@lang('site.signatories')</th>
                                        <th>@lang('site.total-signed')</th>
                                        <th>@lang('site.enabled')</th>
                                        <th>@lang('site.added-on')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($contracts as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td @if(!empty($item->description)) data-toggle="tooltip" data-placement="top" title="{{ $item->description }}"  @endif >{{ $item->name }}</td>
                                        <td data-toggle="tooltip" data-placement="top" title="@foreach($item->users as $user) {{ $user->name }} ({{ roleName($user->role_id) }}) @if(!$loop->last),  @endif @endforeach"  >{{ $item->users()->count() }}</td>
                                        <td @if($item->users()->wherePivot('signed',1)->count()>0) data-toggle="tooltip" data-placement="top" title="@foreach($item->users()->wherePivot('signed',1)->get() as $user) {{ $user->name }} ({{ roleName($user->role_id) }}) @if(!$loop->last),  @endif @endforeach" @endif  >{{ $item->users()->wherePivot('signed',1)->count() }}</td>
                                        <td>{{ boolToString($item->enabled) }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
                                        <td>

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->
                                                    @can('access','view_contract')
                                                    <a class="dropdown-item" href="{{ url('/admin/contracts/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_contract')
                                                    <a class="dropdown-item" href="{{ url('/admin/contracts/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan

                                                    @can('access','delete_contract')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan

                                                    @can('access','send_contract')
                                                    <a class="dropdown-item   @if(empty($item->enabled)) disabled @endif" href="{{ route('admin.contracts.send',['contract'=>$item->id]) }}">@lang('site.notify-signatories')</a>
                                                    @endcan

                                                    @can('access','view_contract')
                                                        <a class="dropdown-item" href="{{ route('admin.contracts.download',['contract'=>$item->id]) }}">@lang('site.download')</a>
                                                    @endcan




                                                </div>
                                            </div>
                                            @can('access','delete_contract')
                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/contracts' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp""display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $contracts->appends(request()->input())->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
