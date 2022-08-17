@extends('layouts.auth')
@section('page-title',__('site.employer-registration'))

@section('content')

    <div class="az-signin-wrapper">

        <div class="az-card-signin">

                    <a  href="{{ route('homepage') }}">
                        @if(!empty(setting('image_logo')))
                            <img class="img-fluid"      src="{{ asset(setting('image_logo')) }}"   >
                        @else
                            <h1 class="az-logo">{{ setting('general_site_name') }}</h1>
                        @endif
                    </a>


                    <div class="az-signin-header">
                        <h2>@lang('site.employer-registration')</h2>
                        <h4>@lang('site.complete-registration')</h4>

                        @include('partials.flash_message')

                        <form method="post" action="{{ route('social.save-employer') }}"  enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label>@lang('site.telephone')</label>
                                <input type="text" class="form-control @error('telephone') is-invalid @enderror"  name="telephone"  required autocomplete="telephone" autofocus placeholder="@lang('site.enter-telephone')" value="{{ old('telephone',$user->phone) }}">
                                @error('telephone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div><!-- form-group -->


                            @foreach($groups as $group)
                                <h5>{{ $group->name }}</h5>

                                @foreach($group->employerFields()->orderBy('sort_order')->get() as $field)
                                    <?php

                                        $value= old('field_'.$field->id);
                                    ?>
                                    @if($field->type=='text')
                                        <div class="form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                            <label for="{{ 'field_'.$field->id }}">{{ $field->name }} @if(empty($field->required))(@lang('site.optional'))@endif</label>
                                            <input placeholder="{{ $field->placeholder }}" @if(!empty($field->required))required @endif  type="text" class="form-control" id="{{ 'field_'.$field->id }}" name="{{ 'field_'.$field->id }}" value="{{ $value }}">
                                            @if ($errors->has('field_'.$field->id))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    @elseif($field->type=='select')
                                        <div class="form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                            <label for="{{ 'field_'.$field->id }}">{{ $field->name }} @if(empty($field->required))(@lang('site.optional'))@endif</label>
                                            <?php
                                            $options = nl2br($field->options);
                                            $values = explode('<br />',$options);
                                            $selectOptions = [];
                                            foreach($values as $value2){

                                                $selectOptions[trim($value2)]=trim($value2);
                                            }
                                            ?>
                                            {{ Form::select('field_'.$field->id, $selectOptions,$value,['placeholder' => $field->placeholder,'class'=>'form-control']) }}
                                            @if ($errors->has('field_'.$field->id))
                                                <span class="help-block">
                                                                                        <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                                                                    </span>

                                            @endif
                                        </div>
                                    @elseif($field->type=='textarea')
                                        <div class="form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                            <label for="{{ 'field_'.$field->id }}">{{ $field->name }} @if(empty($field->required))(@lang('site.optional'))@endif</label>
                                            <textarea placeholder="{{ $field->placeholder }}" class="form-control" name="{{ 'field_'.$field->id }}" id="{{ 'field_'.$field->id }}" @if(!empty($field->required))required @endif  >{{ $value }}</textarea>
                                            @if ($errors->has('field_'.$field->id))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    @elseif($field->type=='checkbox')
                                        <div class="checkbox">
                                            <label>
                                                <input name="{{ 'field_'.$field->id }}" type="checkbox" value="1" @if($value==1) checked @endif> {{ $field->name }}
                                            </label>
                                        </div>

                                    @elseif($field->type=='radio')
                                        <?php
                                        $options = nl2br($field->options);
                                        $values = explode('<br />',$options);
                                        $radioOptions = [];
                                        foreach($values as $value3){
                                            $radioOptions[$value3]=trim($value3);
                                        }
                                        ?>
                                        <h5><strong>{{ $field->name }}</strong></h5>
                                        @foreach($radioOptions as $value2)
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" @if($value==$value2) checked @endif  name="{{ 'field_'.$field->id }}" id="{{ 'field_'.$field->id }}-{{ $value2 }}" value="{{ $value2 }}" >
                                                    {{ $value2 }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @elseif($field->type=='file')
                                        <?php

                                            $value='';
                                        ?>


                                            <div class="form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                <label for="{{ 'field_'.$field->id }}">{{ $field->name }} @if(empty($field->required))(@lang('site.optional'))@endif</label>
                                                <input placeholder="{{ $field->placeholder }}" @if(!empty($field->required))required @endif  type="file" class="form-control" id="{{ 'field_'.$field->id }}" name="{{ 'field_'.$field->id }}" >
                                                @if ($errors->has('field_'.$field->id))
                                                    <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                                @endif
                                            </div>







                                    @endif


                                @endforeach


                            @endforeach


                            <button  type="submit" class="btn btn-az-primary btn-block">@lang('site.sign-up')</button>
                        </form>
                    </div><!-- az-signin-header -->






        </div><!-- az-card-signin -->

    </div><!-- az-signin-wrapper -->

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/confirm.css') }}">


    @endsection
