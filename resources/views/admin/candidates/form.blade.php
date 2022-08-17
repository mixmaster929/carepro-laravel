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
                    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($candidate->name) ? $candidate->name : '') }}" >
                    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
                </div>
                <div class="form-group col-md-6 {{ $errors->has('display_name') ? 'has-error' : ''}}">
                    <label for="display_name" class="control-label"><span class="req">*</span>@lang('site.display-name')</label>
                    <input required  class="form-control" name="display_name" type="text" id="display_name" value="{{ old('display_name',isset($candidate->candidate->display_name) ? $candidate->candidate->display_name : '') }}" >
                    {!! clean( $errors->first('display_name', '<p class="help-block">:message</p>') ) !!}
                </div>
                </div>
                <div class="row">
                <div class="form-group col-md-6 {{ $errors->has('email') ? 'has-error' : ''}}">
                    <label for="email" class="control-label"><span class="req">*</span>@lang('site.email')</label>
                    <input required  class="form-control" name="email" type="text" id="email" value="{{ old('email',isset($candidate->email) ? $candidate->email : '') }}" >
                    {!! clean( $errors->first('email', '<p class="help-block">:message</p>') ) !!}
                </div>
                <div class="form-group col-md-6 {{ $errors->has('telephone') ? 'has-error' : ''}}">
                    <label for="telephone" class="control-label">@lang('site.telephone')</label>
                    <input class="form-control" name="telephone" type="text" id="telephone" value="{{ old('telephone',isset($candidate->telephone) ? $candidate->telephone : '') }}" >
                    {!! clean( $errors->first('telephone', '<p class="help-block">:message</p>') ) !!}
                </div>
                </div>
                <div class="row">
                <div class="form-group col-md-6 {{ $errors->has('gender') ? 'has-error' : ''}}">
                    <label for="gender" class="control-label"><span class="req">*</span>@lang('site.gender')</label>
                    <select  required name="gender" class="form-control" id="gender" >
                        @foreach (json_decode('{"f":"'.__('site.female').'","m":"'.__('site.male').'"}', true) as $optionKey => $optionValue)
                            <option value="{{ $optionKey }}" {{ ((null !== old('gender',@$candidate->candidate->gender)) && old('gender',@$candidate->candidate->gender) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                        @endforeach
                    </select>
                    {!! clean( $errors->first('gender', '<p class="help-block">:message</p>') ) !!}
                </div>
                <div class="form-group col-md-6  {{ $errors->has('date_of_birth') ? 'has-error' : ''}}">
                    <label for="date_of_birth" class="control-label"><span class="req">*</span>@lang('site.date-of-birth')</label>

                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            if(isset($candidate)){
                                $byear = \Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->year;
                            }
                            else{
                                $byear = null;
                            }
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
                                if(isset($candidate)){
                                    $bmonth = \Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->month;
                                }
                                else{
                                    $bmonth = null;
                                }
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
                                if(isset($candidate)){
                                    $bday = \Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->day;
                                }
                                else{
                                    $bday = null;
                                }
                                ?>
                                @foreach(range(1,31) as $day)
                                    <option {{ ((null !== old('date_of_birth_day',$bday)) && old('date_of_birth_day',$bday) == $day) ? 'selected' : ''}}  value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {!! clean( $errors->first('date_of_birth', '<p class="help-block">:message</p>') ) !!}
                </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group  {{ $errors->has('picture') ? 'has-error' : ''}}">
                            <label for="picture" class="control-label">@if($formMode=='edit')@lang('site.change')    @endif @lang('site.picture')</label>


                            <input class="form-control" name="picture" type="file" id="picture" value="{{ isset($candidate->candidate->picture) ? $candidate->candidate->picture : ''}}" >
                            {!! clean( $errors->first('picture', '<p class="help-block">:message</p>') ) !!}
                        </div>

                        @if($formMode==='edit' && !empty($candidate->candidate->picture))

                            <div ><img   data-toggle="modal" data-target="#pictureModal" src="{{ asset($candidate->candidate->picture) }}" class="int_thmwpoin" /></div> <br/>
                            <a  onclick="return confirm('@lang('site.delete-prompt')')" class="btn btn-danger int_tpmb" href="{{ route('admin.candidate.remove-picture',['id'=>$candidate->id]) }}"><i class="fa fa-trash"></i> @lang('site.delete') @lang('site.picture')</a>



                            <div class="modal fade" id="pictureModal" tabindex="-1" role="dialog" aria-labelledby="pictureModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="pictureModalLabel">@lang('site.picture')</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body int_txcen"  >
                                            <img src="{{ asset($candidate->candidate->picture) }}" class="int_txcen" />
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        @endif

                    </div>
                    @if($formMode === 'edit')
                    <div class="form-group col-md-6 {{ $errors->has('video_code') ? 'has-error' : ''}}">
                        <label for="video_code" class="control-label">@lang('site.video-embed-code')</label>
                        <textarea id="video_code" name="video_code" class="form-control">{{ old('video_code',isset($candidate->candidate->video_code) ? $candidate->candidate->video_code : '') }}</textarea>
                        {!! clean( $errors->first('video_code', '<p class="help-block">:message</p>') ) !!}
                    </div>
                    @else
                        <div class="form-group col-md-6 {{ $errors->has('video_url') ? 'has-error' : ''}}">
                            <label for="video_url" class="control-label">@lang('site.video-url')</label>
                             <input placeholder="e.g. https://www.youtube.com/watch?v=8MLXN45zLoM" class="form-control" type="text" name="video_url" value="{{ old('video_url') }}" />

                            {!! clean( $errors->first('video_url', '<p class="help-block">:message</p>') ) !!}
                        </div>

                    @endif
                </div>
                <div class="row">
                    <?php
                    if($formMode=='edit' && isset($candidate)){
                        $value = old('cv_path',$candidate->candidate->cv_path);

                    }
                    else{
                        $value='';
                    }
                    ?>

                    <div class="col-md-12 row">
                        <div class="col-md-6 form-group{{ $errors->has('cv_path') ? ' has-error' : '' }}">
                            <label for="{{ 'cv_path' }}">@lang('site.cv-resume'):</label>
                            <input type="file" class="form-control" id="{{ 'cv_path' }}" name="{{ 'cv_path' }}" >
                            @if ($errors->has('cv_path'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('cv_path') }}</strong>
                                        </span>
                            @endif
                        </div>

                        <div class="col-md-6">


                            @if(!empty($value))
                                <h3>{{ basename($value) }}</h3>
                                @if(isImage($value))
                                    <div><img  data-toggle="modal" data-target="#pictureModalcv" src="{{ route('admin.image') }}?file={{ $value }}"  class="int_w330cur" /></div> <br/>


                                    <div class="modal fade" id="pictureModalcv" tabindex="-1" role="dialog" aria-labelledby="pictureModalcvLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="pictureModalcvLabel">@lang('site.picture')</h5>
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
                                <a onclick="return confirm('@lang('site.delete-prompt')')" class="btn btn-danger" href="{{ route('admin.candidate.remove-cv',['id'=>$candidate->id]) }}"><i class="fa fa-trash"></i> @lang('site.delete-file')</a>
                                <a class="btn btn-success" href="{{ route('admin.download') }}?file={{ $value }}"><i class="fa fa-download"></i> @lang('site.download')</a>
                            @endif
                        </div>


                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="categories">@lang('site.categories')</label>
                        @if($formMode === 'edit')
                            <select multiple name="categories[]" id="categories" class="form-control select2">
                                <option></option>
                                @foreach(\App\Category::orderBy('name')->get() as $category)
                                    <option  @if( (is_array(old('categories')) && in_array(@$category->id,old('categories')))  || (null === old('categories')  && $candidate->candidate->categories()->where('id',$category->id)->first() ))
                                        selected
                                        @endif
                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <select  multiple name="categories[]" id="categories" class="form-control select2">
                                <option></option>
                                @foreach(\App\Category::orderBy('name')->get() as $category)
                                    <option @if(is_array(old('categories')) && in_array(@$category->id,old('categories'))) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="row">
                <div class="form-group col-md-6 {{ $errors->has('employed') ? 'has-error' : ''}}">
                    <label for="employed" class="control-label">@lang('site.employed')</label>
                    <select name="employed" class="form-control" id="employed" >
                        @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                            <option value="{{ $optionKey }}" {{ ((null !== old('employed',@$candidate->candidate->employed)) && old('employed',@$candidate->candidate->employed) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                        @endforeach
                    </select>
                    {!! clean( $errors->first('employed', '<p class="help-block">:message</p>') ) !!}
                </div>
                <div class="form-group col-md-6 {{ $errors->has('shortlisted') ? 'has-error' : ''}}">
                    <label for="shortlisted" class="control-label">@lang('site.shortlisted')</label>
                    <select name="shortlisted" class="form-control" id="shortlisted" >
                        @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                            <option value="{{ $optionKey }}" {{ ((null !== old('shortlisted',@$candidate->candidate->shortlisted)) && old('shortlisted',@$candidate->candidate->shortlisted) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                        @endforeach
                    </select>
                    {!! clean( $errors->first('shortlisted', '<p class="help-block">:message</p>') ) !!}
                </div>
                </div>
                <div class="row">
                <div class="form-group col-md-6 {{ $errors->has('locked') ? 'has-error' : ''}}">
                    <label for="locked" class="control-label">@lang('site.locked')</label>
                    <select name="locked" class="form-control" id="locked" >
                        @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                            <option value="{{ $optionKey }}" {{ ((null !== old('locked',@$candidate->candidate->locked)) && old('locked',@$candidate->candidate->locked) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                        @endforeach
                    </select>
                    {!! clean( $errors->first('locked', '<p class="help-block">:message</p>') ) !!}
                </div>
                <div class="form-group col-md-6 {{ $errors->has('status') ? 'has-error' : ''}}">
                    <label for="status" class="control-label">@lang('site.enabled')</label>
                    <select name="status" class="form-control" id="status" >
                        @foreach (json_decode('{"1":"'.__('site.yes').'","0":"'.__('site.no').'"}', true) as $optionKey => $optionValue)
                            <option value="{{ $optionKey }}" {{ ((null !== old('status',@$candidate->candidate->status)) && old('status',@$candidate->candidate->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                        @endforeach
                    </select>
                    {!! clean( $errors->first('status', '<p class="help-block">:message</p>') ) !!}
                </div>
                </div>
                <div class="row">
                <div class="form-group col-md-6 {{ $errors->has('public') ? 'has-error' : ''}}">
                    <label for="public" class="control-label">@lang('site.public')</label>
                    <select name="public" class="form-control" id="public" >
                        @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                            <option value="{{ $optionKey }}" {{ ((null !== old('public',@$candidate->candidate->public)) && old('public',@$candidate->candidate->public) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                        @endforeach
                    </select>
                    {!! clean( $errors->first('public', '<p class="help-block">:message</p>') ) !!}
                </div>
                <div class="form-group col-md-6 {{ $errors->has('password') ? 'has-error' : ''}}">
                    <label for="password" class="control-label">@lang('site.password')</label>
                    <input class="form-control" name="password" type="password" id="password" value="{{ old('password',isset($candidate->candidate->password) ? $candidate->candidate->password : '') }}" >
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
    @foreach(\App\CandidateFieldGroup::orderBy('sort_order')->get() as $group)
    <div class="card">
        <div class="card-header" id="heading{{ $group->id }}">
            <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $group->id }}" aria-expanded="false" aria-controls="collapse{{ $group->id }}">
                    {{ $group->name }}
                </button>
            </h2>
        </div>
        <div id="collapse{{ $group->id }}"  aria-labelledby="heading{{ $group->id }}" >
            <div class="card-body row">
                @foreach($group->candidateFields()->orderBy('sort_order')->get() as $field)
                    <?php
                    if($formMode=='edit' && isset($candidate)){
                     $value = old('field_'.$field->id,($candidate->candidateFields()->where('id',$field->id)->first()) ? $candidate->candidateFields()->where('id',$field->id)->first()->pivot->value:'');

                    }
                    else{
                    $value=old('field_'.$field->id);
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
                            if($formMode=='edit' && isset($candidate)){
                                $value = old('field_'.$field->id,($candidate->candidateFields()->where('id',$field->id)->first()) ? $candidate->candidateFields()->where('id',$field->id)->first()->pivot->value:'');

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
                                        <a onclick="return confirm('@lang('site.delete-prompt')')" class="btn btn-danger" href="{{ route('admin.candidate.remove-file',['fieldId'=>$field->id,'userId'=>$candidate->id]) }}"><i class="fa fa-trash"></i> @lang('site.delete-file')</a>
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
