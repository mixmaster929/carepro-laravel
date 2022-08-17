@extends('layouts.auth')
@section('page-title',__('site.candidate-registration'))

@section('content')

    <div class="az-signin-wrapper">

        <div class="az-card-signin">

            <a  href="{{ route('homepage') }}">
                @if(!empty(setting('image_logo')))
                    <img  class="img-fluid"     src="{{ asset(setting('image_logo')) }}"   >
                @else
                    <h1 class="az-logo">{{ setting('general_site_name') }}</h1>
                @endif
            </a>


            <div class="az-signin-header">
                <h2>@lang('site.candidate-registration')</h2>
                <h4>@lang('site.complete-registration')</h4>

                @include('partials.flash_message')

                <form method="post" action="{{ route('social.save-candidate') }}" enctype="multipart/form-data">
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

                    <div class="form-group  {{ $errors->has('gender') ? 'has-error' : ''}}">
                        <label for="gender" class="control-label">@lang('site.gender')</label>
                        <select  required name="gender" class="form-control" id="gender" >
                            <option></option>
                            @foreach (json_decode('{"f":"'.__('site.female').'","m":"'.__('site.male').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ (null !== old('gender',$gender) && old('gender',$gender) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('gender', '<p class="help-block">:message</p>') ) !!}
                    </div>
                    <div class="form-group  {{ $errors->has('date_of_birth') ? 'has-error' : ''}}">
                        <label for="date_of_birth" class="control-label">@lang('site.date-of-birth')</label>

                        <div class="row">
                            <div class="col-md-4">
                                <?php

                                    $byear = $user->birthYear;

                                ?>
                                <select  required  class="form-control" name="date_of_birth_year" id="date_of_birth_year">
                                    <option  >YYYY</option>

                                    @foreach(array_reverse(range(1930,date('Y'))) as $year)
                                        <option value="{{ $year }}"   {{ ((null !== old('date_of_birth_year',$byear)) && old('date_of_birth_year',$byear) == $year) ? 'selected' : ''}}   >{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select  required  class="form-control" name="date_of_birth_month" id="date_of_birth_month">
                                    <option  >MM</option>
                                    <?php
                                        $bmonth = $user->birthMonth;
                                    ?>
                                    @foreach(range(1,12) as $month)
                                        <option {{ ((null !== old('date_of_birth_month',$bmonth)) && old('date_of_birth_month',$bmonth) == $month) ? 'selected' : ''}} value="{{ $month }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select  required  class="form-control" name="date_of_birth_day" id="date_of_birth_day">
                                    <option >DD</option>
                                    <?php
                                        $bday = $user->birthDay;
                                    ?>
                                    @foreach(range(1,31) as $day)
                                        <option {{ ((null !== old('date_of_birth_day',$bday)) && old('date_of_birth_day',$bday) == $day) ? 'selected' : ''}}  value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {!! clean( $errors->first('date_of_birth', '<p class="help-block">:message</p>') ) !!}
                    </div>

                    @if(empty($user->photoURL))
                    <div class="form-group  {{ $errors->has('picture') ? 'has-error' : ''}}">
                        <label for="picture" class="control-label">@lang('site.your-profile-picture') (@lang('site.optional'))</label>


                        <input class="form-control" name="picture" type="file" id="picture"   >
                        {!! clean( $errors->first('picture', '<p class="help-block">:message</p>') ) !!}
                    </div>
                    @endif

                    <div class="form-group{{ $errors->has('cv_path') ? ' has-error' : '' }}">
                        <label for="{{ 'cv_path' }}">@lang('site.cv-resume') (@lang('site.optional'))</label>
                        <input type="file" class="form-control" id="{{ 'cv_path' }}" name="{{ 'cv_path' }}" >
                        {!! clean( $errors->first('cv_path', '<p class="help-block">:message</p>') ) !!}
                    </div>

                    @if(\App\Category::count()>0)
                    <div class="form-group ">
                        <label for="categories">@lang('site.categories')</label>

                            <select  multiple name="categories[]" id="categories" class="form-control select2">

                                @foreach(\App\Category::orderBy('name')->get() as $category)
                                    <option @if(is_array(old('categories')) && in_array(@$category->id,old('categories'))) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        <p class="help-block">@lang('site.categories-hint')</p>

                    </div>
                    @endif



                    @foreach($groups as $group)
                        <h5>{{ $group->name }}</h5>

                        @foreach($group->candidateFields()->orderBy('sort_order')->get() as $field)
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
                                    $selectOptions = [''=>''];
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
    <link rel="stylesheet" href="{{ asset('css/admin/candidate-auth.css') }}">


    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
@endsection

@section('footer')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/select.js') }}"></script>

@endsection
