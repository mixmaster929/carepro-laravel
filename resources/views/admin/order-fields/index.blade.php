@extends('layouts.admin-page')



@section('pageTitle',$orderFieldGroup->orderForm->name)
@section('page-title',__('site.section-fields').': '.$orderFieldGroup->name)
@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=> route('admin.order-forms.index'),
                'page'=>__('site.order-forms')
            ],
            [
                'link'=>route('admin.order-field-groups.index',['orderForm'=>$orderFieldGroup->order_form_id]),
                'page'=>__('site.form-sections')
            ],
            [
                'link'=>'#',
                'page'=>__('site.form-fields')
            ]
    ]])
@endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        <a href="{{ route('admin.order-field-groups.index',['orderForm'=>$orderFieldGroup->order_form_id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ route('admin.order-fields.create',['orderFieldGroup'=>$orderFieldGroup->id])}}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.name')</th>
                                        <th>@lang('site.type')</th>
                                        <th>@lang('site.sort-order')</th>
                                        <th>@lang('site.required')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($orderfields as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ ucfirst($item->type) }}</td>
                                        <td>{{ $item->sort_order }}</td>
                                        <td>{{ boolToString($item->required) }}</td>
                                        <td>

                                            <a href="{{ url('/admin/order-fields/' . $item->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('site.edit')</button></a>

                                            <form method="POST" action="{{ url('/admin/order-fields' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('site.delete')</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $orderfields->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
