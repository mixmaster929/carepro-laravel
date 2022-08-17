@extends('layouts.admin-page-wide')
@section('pageTitle',__('site.import-candidate'))
@section('page-title',__('site.import-candidate'))

@section('page-content')

    <div class="container-fluid" >
        <div class="row justify-content-center mt-0">
            <div class="text-center p-0 mt-3 mb-2 int_hpw"  >
                <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                    <div class="row">
                        <div class="col-md-12 mx-0">

                                <!-- progressbar -->
                                <ul id="progressbar">
                                    <li @if($active==1) class="active" @endif id="account"><a href="{{ route('admin.candidates.import-1') }}"><strong>@lang('site.upload-file')</strong></a></li>
                                    <li @if($active==2) class="active" @endif   id="personal"><a href="{{ route('admin.candidates.import-2') }}"><strong>@lang('site.set-fields')</strong></a></li>
                                    <li @if($active==3) class="active" @endif  id="payment"><a href="{{ route('admin.candidates.import-preview') }}"><strong>@lang('site.preview')</strong></a></li>
                                    <li @if($active==4) class="active" @endif  id="confirm"><a href="{{ route('admin.candidates.import-complete') }}"><strong>@lang('site.complete')</strong></a></li>
                                </ul> <!-- fieldsets -->

                                @yield('form-content')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/candidates-import.css') }}">

    @endsection

