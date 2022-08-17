@extends('layouts.admin-page')

@section('pageTitle',__('site.order-form').': '.$orderform->name)
@section('page-title',__('site.order-form').': '.$orderform->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ url('/admin/order-forms') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ url('/admin/order-forms/' . $orderform->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>

                        <form method="POST" action="{{ url('admin/order-forms' . '/' . $orderform->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>@lang('site.id')</th><td>{{ $orderform->id }}</td>
                                    </tr>
                                    <tr><th> @lang('site.name') </th><td> {{ $orderform->name }} </td></tr><tr><th> @lang('site.description') </th><td> {!! clean( $orderform->description ) !!} </td></tr>

                                    <tr><th> @lang('site.enabled') </th><td> {{ boolToString($orderform->enabled) }} </td></tr>
                                    <tr><th> @lang('site.show-shortlist') </th><td> {{ boolToString($orderform->shortlist) }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
