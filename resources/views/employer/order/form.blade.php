@extends($templateLayout)

@section('page-title',$orderForm->name)
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('order-forms') }}">@lang('site.order-forms')</a></li>
    <li class="breadcrumb-item">@lang('site.form')</li>
@endsection


@section('content')

    <form id="wizardform" method="post" action="{{ route('order.save-form',['orderForm'=>$orderForm->id]) }}" enctype="multipart/form-data">
        @csrf
        <div id="form-container">

            @if(!empty($orderForm->description) && !request()->exists('nodesc'))
                <h3>@lang('site.description')</h3>
                <section>
                    <p>{!! clean( $orderForm->description ) !!}</p>
                </section>

           @endif



            @if($orderForm->shortlist==1 && setting('order_enable_shortlist')==1)
                <h3>@lang('site.shortlist')({{ strtolower(__('site.optional')) }})</h3>
                <section>

                    @if(!is_array($cart) || count($cart)==0)
                        @lang('site.order-shortlist-text') <br/>
                        <a class="btn btn-primary rounded" href="{{ route('profiles') }}">@lang('site.browse-profiles')</a>
                    @else
                        <div class="row">
                            @foreach($cart as $item)

                                <?php
                                $candidate = \App\Candidate::find($item);
                                ?>

                                <div class="card col-md-3 bd-0 rounded">

                                    @if(!empty($candidate->picture) && file_exists($candidate->picture))
                                        <img  class="img-fluid"   src="{{ asset($candidate->picture) }}">
                                    @elseif($candidate->gender=='m')
                                        <img  class="img-fluid"   src="{{ asset('img/man.jpg') }}">
                                    @else
                                        <img  class="img-fluid"   src="{{ asset('img/woman.jpg') }}">
                                    @endif
                                    <div class="card-body bd bd-t-0">
                                        <h5 class="card-title">{{ $candidate->display_name }}</h5>
                                        <p class="card-text">
                                            <strong>@lang('site.age'):</strong> {{ getAge(\Illuminate\Support\Carbon::parse($candidate->date_of_birth)->timestamp) }}
                                            <br/>
                                            <strong>@lang('site.gender'):</strong> {{ gender($candidate->gender) }}
                                        </p>
                                        <a target="_blank" href="{{ route('profile',['candidate'=>$candidate->id]) }}" class="card-link  btn btn-sm btn-primary rounded">@lang('site.view-profile')</a>
                                    </div>
                                </div><!-- card -->


                            @endforeach
                        </div>
                        <div class="int_mt30px"><a class="btn btn-success" href="{{ route('shortlist') }}"><i class="fa fa-edit"></i> @lang('site.modify-shortlist')</a></div>
                    @endif
                </section>
            @endif



            @foreach($orderForm->orderFieldGroups()->orderBy('sort_order')->get() as $group)
                <?php
                        if($group->layout=='v'){
                            $col = '12';
                        }
                    else{
                        $col = $group->columns;
                    }
                        ?>
            <h3>{{ $group->name }}</h3>

            <section>

                @if(!empty($group->description))
                    <p>
                        {!! clean( $group->description ) !!}
                    </p>
                    <hr/>
                @endif
                <div class="row">
                @foreach($group->orderFields()->orderBy('sort_order')->get() as $field)
                        <?php
                            $value='';
                        ?>

                            @if($field->type=='text')
                                <div class="form-group col-md-{{ $col  }}{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                    <label for="{{ 'field_'.$field->id }}">@if(!empty($field->required))@endif{{ $field->name }}</label>
                                    <input placeholder="{{ $field->placeholder }}" @if(!empty($field->required))required @endif  type="text" class="form-control" id="{{ 'field_'.$field->id }}" name="{{ 'field_'.$field->id }}" value="{{ $value }}">
                                    @if ($errors->has('field_'.$field->id))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @elseif($field->type=='label')
                                <div class="col-md-12">
                                    <h4>{{ $field->name }}</h4>
                                </div>

                            @elseif($field->type=='select')
                                <div class="form-group col-md-{{ $col  }}{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                    <label for="{{ 'field_'.$field->id }}">@if(!empty($field->required))@endif{{ $field->name }}</label>
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
                                <div class="form-group col-md-{{ $col  }}{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}</label>
                                    <textarea placeholder="{{ $field->placeholder }}" class="form-control" name="{{ 'field_'.$field->id }}" id="{{ 'field_'.$field->id }}" @if(!empty($field->required))required @endif  >{{ $value }}</textarea>
                                    @if ($errors->has('field_'.$field->id))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @elseif($field->type=='checkbox')
                                <div class="checkbox col-md-{{ $col  }}">
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
                                    <div class="radio  col-md-{{ $col  }}">
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


                                    <div class="col-md-{{ $col  }} form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                        <label for="{{ 'field_'.$field->id }}">@if(!empty($field->required))@endif{{ $field->name }}</label>
                                        <input placeholder="{{ $field->placeholder }}" @if(!empty($field->required))required @endif  type="file" class="form-control" id="{{ 'field_'.$field->id }}" name="{{ 'field_'.$field->id }}" >
                                        @if ($errors->has('field_'.$field->id))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                        @endif
                                    </div>






                            @endif

                    @endforeach
                </div>
            </section>
            @endforeach


                <h3>
                    @if($orderForm->interview==1)
                        @lang('site.interview-details')
                    @else
                        @lang('site.comments')
                    @endif

                </h3>
                <section>

                    @if($orderForm->interview==1)

                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="interview_date" class="control-label">@lang('site.preferred-interview-date')</label>
                                <input class="form-control date" name="interview_date" type="text" id="interview_date" value="{{ old('interview_date') }}" >
                                {!! clean( $errors->first('interview_date', '<p class="help-block">:message</p>') ) !!}
                            </div>

                            <div class="form-group col-md-6">
                                <label for="interview_location" class="control-label">@lang('site.interview-location')</label>

                                <textarea placeholder="@lang('site.interview-location-placeholder')" class="form-control" name="interview_location" id="interview_location" >{{ old('interview_location') }}</textarea>
                                {!! clean( $errors->first('interview_location', '<p class="help-block">:message</p>') ) !!}
                            </div>

                        </div>



                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="interview_time" class="control-label">@lang('site.interview-time')</label>
                                <input placeholder="e.g. 3pm" class="form-control" name="interview_time" type="text" id="interview_time" value="{{ old('interview_time') }}" >
                                {!! clean( $errors->first('interview_time', '<p class="help-block">:message</p>') ) !!}
                            </div>

                        </div>

                    @endif

                    <div class="row">

                        <div class="form-group col-md-12">
                            <label for="comments" class="control-label">@lang('site.comments')</label>
                            <textarea class="form-control" id="comments"  name="comments">{{ old('comments') }}</textarea>
                            {!! clean( $errors->first('comments', '<p class="help-block">:message</p>') ) !!}
                        </div>

                    </div>




                </section>


        </div>


    </form>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/jquery.steps/wizard.css') }}"/>
    <link href="{{ asset('vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('vendor/jquery.steps/jquery.steps.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jquery.steps/jquery.form.js') }}"></script>
    <script src="{{ asset('vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/emporder.js') }}" type="text/javascript"></script>

@endsection

