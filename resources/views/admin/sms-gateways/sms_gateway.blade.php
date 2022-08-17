@extends('layouts.admin-page')
@section('pageTitle',__('site.sms-gateways'))
@section('page-title',__('site.sms-gateways'))

@section('page-content')

    <table class="table">
        <thead>
        <tr>
            <th>@lang('site.name')</th>
            <th>
                @lang('site.active')
            </th>
            <th>@lang('site.url')</th>
            <th>@lang('site.actions')</th>
        </tr>
        </thead>
        <tbody>

        @foreach($gateways as $smsGateway)
            @php
                $gateway = \App\SmsGateway::where('code',$smsGateway)->first();
            @endphp
            <tr>
                <td>{{ __(smsInfo($smsGateway)['name']) }}
                    <div><small>
                            {{ smsInfo($smsGateway)['description'] }}
                        </small> </div></td>
                <td>
                    @if($gateway)
                        {{ boolToString($gateway->active) }}
                    @else
                        {{ __lang('no') }}
                    @endif

                </td>
                <td><a target="_blank" href="{{ smsInfo($smsGateway)['url'] }}">{{ smsInfo($smsGateway)['url'] }}</a></td>
                <td>  @if($gateway && $gateway->active==1)
                        <a class="btn btn-success" href="{{ route('admin.edit-sms-gateway',['smsGateway'=>$gateway->id]) }}">@lang('site.edit')</a>
                        <a  class="btn btn-danger" href="{{ route('admin.sms-status',['smsGateway'=>$gateway->id,'status'=>0]) }}">@lang('site.uninstall')</a>

                    @else
                        <a  class="btn btn-primary" href="{{ route('admin.sms-install',['gateway'=>$smsGateway]) }}">@lang('site.install')</a>

                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div>{!! clean( $smsGateways->links() ) !!}</div>
@endsection
