@extends('layouts.admin-page')

@section('pageTitle',__('site.contract').' : '.$contract->name)
@section('page-title',$contract->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        @can('access','view_contracts')
                        <a href="{{ url('/admin/contracts') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_contract')
                        <a href="{{ url('/admin/contracts/' . $contract->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_contract')
                        <form method="POST" action="{{ url('admin/contracts' . '/' . $contract->id) }}" accept-charset="UTF-8" class="int_inlinedisp""display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>@lang('site.id')</th>
                                        <td>{{ $contract->id }}</td>
                                    </tr>
                                    <tr>
                                        <th> @lang('site.name') </th>
                                        <td> {{ $contract->name }} </td>
                                    </tr>
                                    <tr>
                                        <th> @lang('site.description') </th>
                                        <td> {{ $contract->description }} </td>
                                    </tr>
                                    <tr>
                                        <th> @lang('site.signatories') </th>
                                        <td>
                                            <ul>
                                                @foreach($contract->users as $user)
                                                <li>
                                                    <a href="{{ userLink($user) }}">{{ $user->name }}({{ roleName($user->role_id) }})</a> - {{ $user->pivot->signed==1? __('site.signed'):__('site.signature-pending') }}
                                                </li>
                                                @endforeach
                                            </ul>



                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <h3>@lang('site.content')</h3>
                                            {!! $contract->content !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> @lang('site.enabled') </th>
                                        <td> {{ boolToString($contract->enabled) }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
