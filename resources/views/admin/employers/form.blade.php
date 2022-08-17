<div class="accordion" id="accordionExample">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h2 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    @lang('site.general-details')
                </button>
            </h2>
        </div>

        <div id="collapseOne"   aria-labelledby="headingOne" >
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6 {{ $errors->has('name') ? 'has-error' : ''}}">
                        <label for="name" class="control-label"><span class="req">*</span>@lang('site.name')</label>
                        <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($employer->name) ? $employer->name : '') }}" >
                        {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
                    </div>
                    <div class="form-group col-md-6 {{ $errors->has('email') ? 'has-error' : ''}}">
                        <label for="email" class="control-label"><span class="req">*</span>@lang('site.email')</label>
                        <input required class="form-control" name="email" type="text" id="email" value="{{ old('email',isset($employer->email) ? $employer->email : '') }}" >
                        {!! clean( $errors->first('email', '<p class="help-block">:message</p>') ) !!}
                    </div>
                </div>
                <div class="row">

                    <div class="form-group col-md-6 {{ $errors->has('telephone') ? 'has-error' : ''}}">
                        <label for="telephone" class="control-label">@lang('site.telephone')</label>
                        <input class="form-control" name="telephone" type="text" id="telephone" value="{{ old('telephone',isset($employer->telephone) ? $employer->telephone : '') }}" >
                        {!! clean( $errors->first('telephone', '<p class="help-block">:message</p>') ) !!}
                    </div>

                    <div class="form-group col-md-6 {{ $errors->has('gender') ? 'has-error' : ''}}">
                        <label for="gender" class="control-label"><span class="req">*</span>@lang('site.gender')</label>
                        <select required name="gender" class="form-control" id="gender" >
                            @foreach (json_decode('{"f":"'.__('site.female').'","m":"'.__('site.male').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('gender',@$employer->employer->gender)) && old('gender',@$employer->employer->gender) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('gender', '<p class="help-block">:message</p>') ) !!}
                    </div>


                </div>



                <div class="row">

                    <div class="form-group col-md-6 {{ $errors->has('active') ? 'has-error' : ''}}">
                        <label for="active" class="control-label"><span class="req">*</span>@lang('site.active')</label>
                        <select required name="active" class="form-control" id="active" >
                            @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('active',@$employer->employer->active)) && old('active',@$employer->employer->active) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('active', '<p class="help-block">:message</p>') ) !!}
                    </div>

                    <div class="form-group col-md-6 {{ $errors->has('status') ? 'has-error' : ''}}">
                        <label for="status" class="control-label"><span class="req">*</span>@lang('site.enabled')</label>
                        <select required name="status" class="form-control" id="status" >
                            @foreach (json_decode('{"1":"'.__('site.yes').'","0":"'.__('site.no').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('status',@$employer->employer->status)) && old('status',@$employer->employer->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('status', '<p class="help-block">:message</p>') ) !!}
                    </div>

                </div>

                <div class="row">

                        <div class="form-group col-md-6 {{ $errors->has('password') ? 'has-error' : ''}}">
                            <label for="password" class="control-label">@lang('site.password')</label>
                            <input class="form-control" name="password" type="password" id="password" value="{{ old('password',isset($employer->employer->password) ? $employer->employer->password : '') }}" >
                            {!! clean( $errors->first('password', '<p class="help-block">:message</p>') ) !!}
                        </div>
                </div>
                @if($formMode=='create')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" checked type="checkbox" value="1" id="notify" name="notify">
                                <label class="form-check-label" for="notify">
                                    @lang('site.notify-user')
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    @foreach(\App\EmployerFieldGroup::get() as $group)
    <div class="card">
        <div class="card-header" id="heading{{ $group->id }}">
            <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $group->id }}" aria-expanded="false" aria-controls="collapse{{ $group->id }}">
                    {{ $group->name }}
                </button>
            </h2>
        </div>
        <div id="collapse{{ $group->id }}"   aria-labelledby="heading{{ $group->id }}" data-parent="#accordionExample">
            <div class="card-body row">
                @foreach($group->employerFields()->orderBy('sort_order')->get() as $field)
                    <?php
                    if($formMode=='edit' && isset($employer)){
                     $value = old('field_'.$field->id,($employer->employerFields()->where('id',$field->id)->first()) ? $employer->employerFields()->where('id',$field->id)->first()->pivot->value:'');

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
                            if($formMode=='edit' && isset($employer)){
                                $value = old('field_'.$field->id,($employer->employerFields()->where('id',$field->id)->first()) ? $employer->employerFields()->where('id',$field->id)->first()->pivot->value:'');

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
                                        <a onclick="return confirm('@lang('site.delete-prompt')')" class="btn btn-danger" href="{{ route('admin.employer.remove-file',['fieldId'=>$field->id,'userId'=>$employer->id]) }}"><i class="fa fa-trash"></i> @lang('site.delete-file')</a>
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
