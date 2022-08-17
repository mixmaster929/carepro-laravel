

<div class="form-group {{ $errors->has('sms_gateway_id') ? 'has-error' : ''}}">
    <label for="sms_gateway_id" class="control-label required">@lang('site.select-gateway')</label>
     <select required class="form-control" name="sms_gateway_id" id="sms_gateway_id">
        <option></option>
        @foreach(\App\SmsGateway::where('active',1)->orderBy('gateway_name')->get() as $gateway)
            <option @if(old('sms_gateway_id',isset($sm->sms_gateway_id) ? $sm->sms_gateway_id : '')==$gateway->id) selected  @endif value="{{ $gateway->id }}">{{ $gateway->gateway_name }}</option>
            @endforeach
    </select>

    {!! clean( $errors->first('sms_gateway_id', '<p class="help-block">:message</p>') ) !!}
</div>


@if($formMode === 'create')
<div>
    <label class="required">@lang('site.recipients')</label>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#home">@lang('site.users')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#menu1">@lang('site.candidates')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#menu2">@lang('site.employers')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#menu3">@lang('site.telephone-numbers')</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content tabstyle" >
        <div class="tab-pane container active int_plpr" id="home" >
            <br/>
            <div class="form-group col-md-12 {{ $errors->has('users') ? 'has-error' : ''}}">

                <label for="users">@lang('site.select-users')</label>

                    <select  multiple name="users[]" id="users" class="form-control select2_"></select>


                {!! clean( $errors->first('users', '<p class="help-block">:message</p>') ) !!}
            </div>
        </div>
        <div class="tab-pane container fade" id="menu1">
            <br/>

            <div class="form-check">
                <input   autocomplete="off"   class="form-check-input" type="checkbox" value="1" id="all_candidates" name="all_candidates">
                <label class="form-check-label" for="all_candidates">
                    @lang('site.all-candidates')
                </label>
            </div>
            <br/>

            <div class="form-group" id="categoryBox">
                <label for="categories">@lang('site.categories')</label>

                    <select  multiple name="categories[]" id="categories" class="form-control select2">
                        <option></option>
                        @foreach(\App\Category::orderBy('name')->get() as $category)
                            <option @if(is_array(old('categories')) && in_array(@$category->id,old('categories'))) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

            </div>


        </div>
        <div class="tab-pane container fade" id="menu2">
            <br/>
            <div class="form-check">
                <input   autocomplete="off"   class="form-check-input" type="checkbox" value="1" id="all_employers" name="all_employers">
                <label class="form-check-label" for="all_employers">
                    @lang('site.all-employers')
                </label>
            </div>
            <br/>
        </div>

        <div class="tab-pane container fade" id="menu3">
            <br/>
            <div class="form-group">
                <label for="telephone_numbers">@lang('site.telephone-numbers')</label>
                <textarea class="form-control" name="telephone_numbers" id="telephone_numbers" rows="5" placeholder="@lang('site.enter-new-line')"></textarea>
            </div>

            </div>
    </div>


</div>
@endif
<br/>

<div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
    <label for="message" class="control-label required">@lang('site.message')</label>
    <textarea required class="form-control" rows="5" name="message" type="textarea" id="message" >{{ old('message',isset($sm->message) ? $sm->message : '') }}</textarea>
    {!! clean( $errors->first('message', '<p class="help-block">:message</p>') ) !!}
    <p>
        <span id="remaining">160 @lang('site.characters-remaining').</span>
        <span id="messages">1 @lang('site.message_s')</span>
    </p>
</div>
<div class="form-group">
    <label for="template">@lang('site.load-template')</label>
    <select autocomplete="off" class="form-control select2_" name="template" id="template">
        <option></option>
        @foreach(\App\SmsTemplate::limit(1000)->orderBy('name')->get() as $template)
            <option value="{{ $template->id }}">{{ $template->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group {{ $errors->has('comment') ? 'has-error' : ''}}">
    <label for="comment" class="control-label">@lang('site.comment')</label>
    <input class="form-control" name="comment" type="text" id="comment" value="{{ old('comment',isset($sm->comment) ? $sm->comment : '') }}" >
    {!! clean( $errors->first('comment', '<p class="help-block">:message</p>') ) !!}
</div>


@if($formMode=='create')
    <div class="form-group">
        <div class="form-check form-check-inline">
            <input  autocomplete="off"  checked class="form-check-input send" type="radio" name="sent" id="inlineRadio1" value="1">
            <label class="form-check-label" for="inlineRadio1">@lang('site.send-now')</label>
        </div>
        <div class="form-check form-check-inline">
            <input  autocomplete="off"  class="form-check-input send" type="radio" name="sent" id="inlineRadio2" value="0">
            <label class="form-check-label" for="inlineRadio2">@lang('site.send-later')</label>
        </div>
    </div>
@endif

<div id="dateBox"class=" @if($formMode=='create') int_hide @endif  form-group {{ $errors->has('send_date') ? 'has-error' : ''}}">
    <label for="send_date" class="control-label">@lang('site.send-date')</label>
    <input class="form-control date" name="send_date" type="text" id="send_date" value="{{ old('send_date',isset($sm->send_date) ? \Illuminate\Support\Carbon::parse($sm->send_date)->toDateString() : '') }}" >
    {!! clean( $errors->first('send_date', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
