<div class="accordion" id="accordionExample">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h2 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    @lang('site.general-details')
                </button>
            </h2>
        </div>

        <div id="collapseOne" aria-labelledby="headingOne" >
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6 {{ $errors->has('user_id') ? 'has-error' : ''}}">
                        <label for="user_id" class="control-label"><span class="req">*</span>@lang('site.employer')</label>

                        <select required  name="user_id" id="user_id" class="form-control">
                            <?php
                            $userId = old('user_id',@$order->user_id);
                            ?>
                            @if($userId)
                                <option selected value="{{ $userId }}">{{ \App\User::find($userId)->name }} &lt;{{ \App\User::find($userId)->email }}&gt; </option>
                            @endif
                        </select>

                        {!! clean( $errors->first('user_id', '<p class="help-block">:message</p>') ) !!}

                    </div>
                    <div class="form-group col-md-6 {{ $errors->has('interview_date') ? 'has-error' : ''}}">
                        <label for="interview_date" class="control-label">@lang('site.interview-date')</label>
                        <input class="form-control date" name="interview_date" type="text" id="interview_date" value="{{ old('interview_date',isset($order->interview_date) ? $order->interview_date : '') }}" >
                        {!! clean( $errors->first('interview_date', '<p class="help-block">:message</p>') ) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 {{ $errors->has('interview_location') ? 'has-error' : ''}}">
                        <label for="interview_location" class="control-label">@lang('site.interview-location')</label>

                        <textarea class="form-control" name="interview_location" id="interview_location" >{{ old('interview_location',isset($order->interview_location) ? $order->interview_location : '') }}</textarea>
                        {!! clean( $errors->first('interview_location', '<p class="help-block">:message</p>') ) !!}
                    </div>

                    <div class="form-group col-md-6 {{ $errors->has('interview_time') ? 'has-error' : ''}}">
                        <label for="interview_time" class="control-label">@lang('site.interview-time')</label>
                        <input class="form-control" name="interview_time" type="text" id="interview_time" value="{{ old('interview_time',isset($order->interview_time) ? $order->interview_time : '') }}" >
                        {!! clean( $errors->first('interview_time', '<p class="help-block">:message</p>') ) !!}
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-12 {{ $errors->has('candidates') ? 'has-error' : ''}}">

                            <label for="candidates">@lang('site.candidates')</label>
                            @if($formMode === 'edit')
                                <select multiple name="candidates[]" id="candidates" class="form-control select2">
                                    @foreach($order->candidates as $candidate)
                                        <option  @if( (is_array(old('candidates')) && in_array(@$candidate->id,old('candidates')))  || (null === old('candidates')))
                                            selected
                                            @endif
                                            value="{{ $candidate->id }}">{{ $candidate->user->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select  multiple name="candidates[]" id="candidates" class="form-control select2">
                                    <option></option>
                                </select>
                            @endif

                        {!! clean( $errors->first('candidates', '<p class="help-block">:message</p>') ) !!}
                    </div>


                </div>



                <div class="row">


                    <div class="form-group col-md-6 {{ $errors->has('status') ? 'has-error' : ''}}">
                        <label for="status" class="control-label"><span class="req">*</span>@lang('site.status')</label>
                        <select required name="status" class="form-control" id="status" >
                            <option value=""></option>
                            @foreach (json_decode('{"p":"'.__('site.pending').'","i":"'.__('site.in-progress').'","c":"'.__('site.completed').'","x":"'.__('site.cancelled').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('status',@$order->status)) && old('status',@$order->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('status', '<p class="help-block">:message</p>') ) !!}
                    </div>

                    <div class="form-group col-md-6 {{ $errors->has('comments') ? 'has-error' : ''}}">
                        <label for="comments" class="control-label">@lang('site.comments')</label>
                       <textarea class="form-control" id="comments"  name="comments">{{ old('comments',isset($order->comments) ? $order->comments : '') }}</textarea>
                         {!! clean( $errors->first('comments', '<p class="help-block">:message</p>') ) !!}
                    </div>

                </div>


                @if($formMode=='create')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" checked type="checkbox" value="1" id="invoice" name="invoice">
                                <label class="form-check-label" for="invoice">
                                    @lang('site.create-invoice')
                                </label>
                            </div>
                        </div>
                    </div>
                @elseif($formMode=='edit')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" checked  type="checkbox" value="1" id="notify" name="notify">
                                <label class="form-check-label" for="notify">
                                    @lang('site.notify-employer')
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    @foreach($orderForm->orderFieldGroups()->orderBy('sort_order')->get() as $group)
        <div class="card">
            <div class="card-header" id="heading{{ $group->id }}">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $group->id }}" aria-expanded="false" aria-controls="collapse{{ $group->id }}">
                        {{ $group->name }}
                    </button>
                </h2>
            </div>
            <div id="collapse{{ $group->id }}"  aria-labelledby="heading{{ $group->id }}"  >
                <div class="card-body row">
                    @foreach($group->orderFields()->orderBy('sort_order')->get() as $field)
                        <?php
                        if($formMode=='edit' && isset($order)){
                            $value = old('field_'.$field->id,($order->orderFields()->where('id',$field->id)->first()) ? $order->orderFields()->where('id',$field->id)->first()->pivot->value:'');

                        }
                        else{
                            $value='';
                        }
                        ?>
                        @if($field->type=='text')
                            <div class="form-group col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                <label for="{{ 'field_'.$field->id }}">@if(!empty($field->required))<span class="req">*</span>@endif{{ $field->name }}:</label>
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
                            <div class="form-group col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                <label for="{{ 'field_'.$field->id }}">@if(!empty($field->required))<span class="req">*</span>@endif{{ $field->name }}:</label>
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
                            <div class="form-group col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                <label for="{{ 'field_'.$field->id }}">@if(!empty($field->required))<span class="req">*</span>@endif{{ $field->name }}:</label>
                                <textarea placeholder="{{ $field->placeholder }}" class="form-control" name="{{ 'field_'.$field->id }}" id="{{ 'field_'.$field->id }}" @if(!empty($field->required))required @endif  >{{ $value }}</textarea>
                                @if ($errors->has('field_'.$field->id))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                @endif
                            </div>
                        @elseif($field->type=='checkbox')
                            <div class="checkbox col-md-6">
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
                                <div class="radio  col-md-6">
                                    <label>
                                        <input type="radio" @if($value==$value2) checked @endif  name="{{ 'field_'.$field->id }}" id="{{ 'field_'.$field->id }}-{{ $value2 }}" value="{{ $value2 }}" >
                                        {{ $value2 }}
                                    </label>
                                </div>
                            @endforeach
                        @elseif($field->type=='file')
                            <?php
                            if($formMode=='edit' && isset($order)){
                                $value = old('field_'.$field->id,($order->orderFields()->where('id',$field->id)->first()) ? $order->orderFields()->where('id',$field->id)->first()->pivot->value:'');

                            }
                            else{
                                $value='';
                            }
                            ?>

                            <div class="col-md-12 row">
                                <div class="col-md-6 form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                    <label for="{{ 'field_'.$field->id }}">@if(!empty($field->required))<span class="req">*</span>@endif{{ $field->name }}:</label>
                                    <input placeholder="{{ $field->placeholder }}" @if(!empty($field->required))required @endif  type="file" class="form-control" id="{{ 'field_'.$field->id }}" name="{{ 'field_'.$field->id }}" >
                                    @if ($errors->has('field_'.$field->id))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-md-6">


                                    @if(!empty($value))
                                        <h3>{{ basename($value) }}</h3>
                                        @if(isImage($value))
                                            <div><img  data-toggle="modal" data-target="#pictureModal{{ $field->id }}" src="{{ route('admin.image') }}?file={{ $value }}"  class="int_w330cur" /></div> <br/>


                                            <div class="modal fade" id="pictureModal{{ $field->id }}" tabindex="-1" role="dialog" aria-labelledby="pictureModal{{ $field->id }}Label" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="pictureModal{{ $field->id }}Label">@lang('site.picture')</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body int_txcen"  >
                                                            <img src="{{ route('admin.image') }}?file={{ $value }}" class="int_txcen" />
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        @endif
                                        <a onclick="return confirm('@lang('site.delete-prompt')')" class="btn btn-danger" href="{{ route('admin.order.remove-file',['fieldId'=>$field->id,'userId'=>$order->id]) }}"><i class="fa fa-trash"></i> @lang('site.delete-file')</a>
                                        <a class="btn btn-success" href="{{ route('admin.download') }}?file={{ $value }}"><i class="fa fa-download"></i> @lang('site.download')</a>
                                    @endif
                                </div>


                            </div>



                        @endif


                    @endforeach


                </div>
            </div>
        </div>
    @endforeach

</div>

<br/>

<div class="form-group col-md-6">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>





