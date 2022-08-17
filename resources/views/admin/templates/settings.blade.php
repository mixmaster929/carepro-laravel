@extends('layouts.admin-page')

@section('pageTitle',__('site.templates'))
@section('page-title',__('site.customize').': '.$template->name)

@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=> route('admin.templates'),
                'page'=>__('site.templates')
            ],
            [
                'link'=>'#',
                'page'=>__('site.customize')
            ],
    ]])
@endsection

@section('page-content')





    <a href="{{ route('admin.templates') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
    <br/><br/>

    <div class="accordion" id="accordionExample">
       @foreach($settings as $key=>$option)
        <div class="card int_overvis"  >
            <div class="card-header" id="heading{{ $key }}">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                        {{ $option['name'] }}
                    </button>
                </h2>
            </div>
            <div id="collapse{{ $key }}" class="collapse" aria-labelledby="heading{{ $key }}" data-parent="#accordionExample">
                <div class="card-body">
                   <p>{{ $option['description'] }}</p>

                    <form class="option-form" action="{{ route('admin.templates.save-options',['option'=>$key]) }}" method="post" enctype="multipart/form-data">
                      @csrf
                        <div class="row">
                            <div class="col-md-3">
                                {{ Form::select('enabled', ['1'=>__('site.enabled'),'0'=>__('site.disabled')], $option['enabled'], ['class'=>'form-control']) }}
                            </div>
                            <div class="col-md-9">
                                <button class="btn btn-primary float-right" type="submit">@lang('site.save-changes')</button>

                            </div>
                        </div>
                        <hr/>
                    @if(file_exists('./templates/'.currentTemplate()->directory.'/assets/previews/'.$key.'.png'))

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">

                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home-{{ $key }}">@lang('site.settings')</a>
                            </li>
                           <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu1-{{ $key }}">@lang('site.demo')</a>
                            </li>

                        </ul>
                    @endif
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active container px-2 pt-4" id="home-{{ $key }}">


                                @include($option['form'],$option['values'])

                            </div>
                            @if(file_exists('./templates/'.currentTemplate()->directory.'/assets/previews/'.$key.'.png'))

                            <div class="tab-pane container px-2 pt-4" id="menu1-{{ $key }}">
                               <img src="{{ tasset('previews/'.$key.'.png') }}" class="img-fluid">


                            </div>
                            @endif

                        </div>






                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>


@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.css') }}">

    <link href="{{ asset('vendor/jquery-ui-1.11.4/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/colorpicker/jquery.colorpicker.css') }}" rel="stylesheet">
@endsection

@section('footer')
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-ui-1.11.4/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/colorpicker/jquery.colorpicker.js') }}"></script>

    <script src="{{ asset('js/admin/textrte.js') }}"></script>

    <script>
"use strict";

        $(document).ready(function(){


            $('.colorpicker-full').colorpicker({
                parts:          'full',
                showOn:         'both',
                buttonColorize: true,
                showNoneButton: true,
                buttonImage : '{{ asset('vendor/colorpicker/images/ui-colorpicker.png') }}'
            });


        $('.option-form').on('submit',function(e){
                e.preventDefault();
                /*Ajax Request Header setup*/
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.toast('@lang('site.saving')');

                /* Submit form data using ajax*/
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'post',
                    data: $(this).serialize(),
                    success: function(response){
                        //------------------------
                        $.toast('@lang('site.changes-saved')');
                        //--------------------------
                    }});
            });
        });



    </script>

    @include('admin.partials.image-browser')

@endsection
