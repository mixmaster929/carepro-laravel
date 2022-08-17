@extends('layouts.admin-page')

@section('pageTitle','apiToken'.' #'.$apitoken->id)
@section('page-title','apiToken'.' #'.$apitoken->id)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ url('/admin/api-tokens') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ url('/admin/api-tokens/' . $apitoken->id . '/edit') }}" title="@lang('site.edit') apiToken"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>

                        <form method="POST" action="{{ url('admin/apitokens' . '/' . $apitoken->id) }}" accept-charset="UTF-8" class="int_inlinedisp""display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete') apiToken" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>@lang('site.id')</th><td>{{ $apitoken->id }}</td>
                                    </tr>
                                    <tr><th> Enabled </th><td> {{ $apitoken->enabled }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
