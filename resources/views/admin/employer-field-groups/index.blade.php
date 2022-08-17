@extends('layouts.admin-page-wide')


@section('pageTitle',__('site.employer-form'))
@section('page-title',__('site.form-sections'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        <a href="{{ url('/admin/employer-field-groups/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('site.name')</th><th>@lang('site.sort-order')</th>
                                        <th>@lang('site.fields')</th>
                                        <th>@lang('site.registration')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($employerfieldgroups as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td><td>{{ $item->sort_order }}</td>
                                        <td>{{ $item->employerFields()->count() }}</td>
                                        <td>{{ boolToString($item->registration) }}</td>
                                        <td>
                                            <a href="{{ route('admin.employer-fields.index',['employerFieldGroup'=>$item->id]) }}" title="@lang('site.manage-fields')"><button class="btn btn-success btn-sm"><i class="fa fa-file-invoice" aria-hidden="true"></i> @lang('site.manage-fields')</button></a>

                                            <a href="{{ url('/admin/employer-field-groups/' . $item->id) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('site.view')</button></a>
                                            <a href="{{ url('/admin/employer-field-groups/' . $item->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('site.edit')</button></a>

                                            <form method="POST" action="{{ url('/admin/employer-field-groups' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('site.delete')</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $employerfieldgroups->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
