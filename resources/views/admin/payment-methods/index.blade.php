@extends('layouts.admin-page')
@section('pageTitle',__('site.payment-methods'))
    @section('page-title',__('site.payment-methods'))

@section('page-content')

    <div class="table-responsive">

        <table class="table">
            <thead>
            <tr>
                <th>@lang('site.payment-method')</th>
                <th>URL</th>
                <th>@lang('site.enabled')</th>

                <th>@lang('site.sort-order')</th>
                <th>@lang('site.actions')</th>
            </tr>
            </thead>
            <tbody>
                @foreach($methods as $directory)
                    @php
                        $method = \App\PaymentMethod::where('code',$directory)->first();
                    @endphp
                    <tr>
                        <td>{{ __(paymentInfo($directory)['name']) }}</td>
                        <td>@if(!empty(paymentInfo($directory)['url'])) <a
                                href="{{ paymentInfo($directory)['url'] }}" target="_blank">{{ paymentInfo($directory)['url'] }}</a> @endif</td>
                        <td>@if($method) {{ boolToString($method->status) }} @endif </td>
                        <td>@if($method)  {{ $method->sort_order }} @endif  </td>
                        <td>
                            @if($method && $method->status==1)
                            <a class="btn btn-sm btn-success" href="{{ route('admin.payment-methods.edit',['paymentMethod'=>$method->id]) }}"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.payment-methods.uninstall',['paymentMethod'=>$method->id]) }}"><i class="fa fa-trash"></i> @lang('site.uninstall')</a>

                            @else
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.payment-methods.install',['method'=>$directory]) }}"><i class="fa fa-download"></i> @lang('site.install')</a>

                            @endif
                        </td>
                    </tr>

                    @endforeach
            </tbody>
        </table>

    </div>

@endsection
