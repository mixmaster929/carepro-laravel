@extends('layouts.admin-page')

@section('pageTitle',__('site.templates'))
@section('page-title',__('site.colors').': '.$template->name)

@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=> route('admin.templates'),
                'page'=>__('site.templates')
            ],
            [
                'link'=>'#',
                'page'=>__('site.colors')
            ],
    ]])
@endsection

@section('page-content')

    <form action="{{ route('admin.templates.save-colors') }}" method="post">
        @csrf

        <table class="table">
            <thead>
                <tr>
                    <th class="int_txcen">@lang('site.original-color')</th>
                    <th>@lang('site.new-color')</th>
                </tr>
            </thead>
            <tbody>

            @foreach($colorList as $color)
                <tr>
                    <td class="int_txcen">
                        @section('header')
                            @parent
                        <style>
                            .cls{{ $loop->index }}{
                                background-color: #{{ $color }}
                            }
                        </style>
                        @endsection
                        <div class="int_colorstyle" class="cls{{ $loop->index }}"></div>
                    #{{ $color }}
                    </td>
                    <td>
                        <div class="input-group myColorPicker">
                        <input type="text" class="form-control colorpicker-full"  name="{{ $color }}_new" @if($template->templateColors()->where('original_color',$color)->first()) value="{{ $template->templateColors()->where('original_color',$color)->first()->user_color }}" @endif>
                        </div>

                    </td>
                </tr>
            @endforeach

            </tbody>

        </table>
        <button class="btn btn-primary btn-block">@lang('site.save-changes')</button>
    </form>

@endsection


@section('header')

    <link href="{{ asset('vendor/jquery-ui-1.11.4/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/colorpicker/jquery.colorpicker.css') }}" rel="stylesheet">
@endsection

@section('footer')
    <script src="{{ asset('vendor/jquery-ui-1.11.4/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/colorpicker/jquery.colorpicker.js') }}"></script>



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

        });
    </script>


@endsection
