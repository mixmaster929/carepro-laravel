@extends('layouts.admin-page')
@section('pageTitle',__("site.setting-".$group))

@section('page-title',__("site.setting-".$group))

@section('page-content')

    <div class="single-pro-review-area mt-t-30 mg-b-15">


        <div class="container-fluid">
            <div class="product-payment-inner-st form-content">


                <form method="POST" action="{{ route('admin.save-settings') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @foreach($settings as $setting)
                        <div class="form-group">
                            <label for="{{ $setting->key }}">@lang('settings.'.$setting->key)</label>
                            @if($setting->type=='text')

                                <input placeholder="{{ $setting->placeholder }}" @if(empty($setting->class)) class="form-control" @else class="{{ $setting->class }}"@endif type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"/>

                            @elseif($setting->type=='include')
                                @include($setting->options)

                                @elseif($setting->type=='textarea')

                                <textarea placeholder="{{ $setting->placeholder }}"  @if(empty($setting->class)) class="form-control" @else class="{{ $setting->class }}" @endif  name="{{ $setting->key }}" id="{{ $setting->key }}">{!! $setting->value !!}</textarea>

                                @elseif($setting->type=='select')
                                <?php



                                if(!empty($setting->options)){
                                    $options = explode(',',$setting->options);
                                    $foptions = [];

                                    foreach($options as $option){
                                        if(preg_match('#=#',$option)) {
                                            $temp = explode('=', $option);
                                            $foptions[$temp[0]] = __('settings.'.strtolower($temp[1]));
                                        }
                                        else{
                                            $foptions[$option]=__('settings.'.strtolower($option));
                                        }

                                    }

                                }
                                else{
                                    $foptions=[];
                                }







                                    if(empty($setting->class)){
                                        $class = 'form-control';
                                    }
                                    else{
                                        $class = $setting->class;
                                    }
                                ?>
                                {{ Form::select($setting->key,$foptions,$setting->value,['placeholder' => $setting->placeholder,'class'=>$class]) }}


                            @elseif($setting->type=='radio')

                                <?php


                                if(!empty($setting->options)){
                                    $options = explode(',',$setting->options);
                                    $foptions = [];

                                    foreach($options as $option){
                                        if(preg_match('#=#',$option)) {
                                            $temp = explode('=', $option);
                                            $foptions[$temp[0]] = __('settings.'.strtolower($temp[1]));
                                        }
                                        else{
                                            $foptions[$option]=__('settings.'.strtolower($option));
                                        }

                                    }

                                }
                                else{
                                    $foptions=[];
                                }
                                ?>

                                @foreach($foptions as $key2=>$value2)
                                    <div class="radio">
                                        <label>
                                            <input type="radio" @if($setting->value==$key2) checked @endif  name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $key2 }}" >
                                            {{ $value2 }}
                                        </label>
                                    </div>
                                @endforeach

                                @elseif($setting->type='image')

                                    @if(!empty($setting->value))
                                    <div><img class="img-responsive thmaxwidth" src="{{ asset($setting->value) }}" /></div>
                                    <br/>
                                    <a class="btn btn-danger" href="{{ route('admin.remove-picture',['setting'=>$setting->id]) }}">@lang('site.remove-picture')</a>
                                    <br/><br/>
                                        @endif

                               <div> <input type="file" name="{{ $setting->key }}"/></div>
                            @endif

                        </div>

                    @endforeach
                    <button class="btn btn-primary btn-block btn-lg" type="submit">@lang('site.save')</button>
                </form>




            </div>
        </div>


    </div>

@endsection


@section('footer')
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('vendor/summernote/summernote-active.js') }}"></script>

    <script src="{{ asset('js/admin/textrte.js') }}"></script>

@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
@endsection
