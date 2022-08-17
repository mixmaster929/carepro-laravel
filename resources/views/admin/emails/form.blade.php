<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
<label for="user_id" class="control-label required">@lang('site.recipient')</label>

<select required  name="user_id" id="user_id" class="form-control">
    @php
    $userId = old('user_id',isset($email->user_id) ? $email->user_id : '');
    @endphp

    @if($userId)
        <option selected value="{{ $userId }}">{{ \App\User::find($userId)->name }} ({{ \App\User::find($userId)->email }}) </option>
    @endif
</select>

{!! clean( check( $errors->first('user_id', '<p class="help-block">:message</p>')) ) !!}
</div>
<div class="form-group {{ $errors->has('cc') ? 'has-error' : ''}}">
    <label for="cc" class="control-label">{{ 'CC' }}</label>
    <input class="form-control" name="cc" type="text" id="cc" value="{{ old('cc',isset($email->cc) ? $email->cc : '') }}" >
    {!! clean( $errors->first('cc', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}">
    <label for="subject" class="control-label required">@lang('site.subject')</label>
    <input required class="form-control" name="subject" type="text" id="subject" value="{{ old('subject',isset($email->subject) ? $email->subject : '') }}" >
    {!! clean( $errors->first('subject', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
    <label for="message" class="control-label">@lang('site.message')</label>
    <textarea class="form-control" rows="5" name="message" type="textarea" id="message" >{{ old('message',isset($email->message) ? $email->message : '') }}</textarea>
    {!! clean( $errors->first('message', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group">
    <label for="templates">@lang('site.load-template')</label>
    <select autocomplete="off" class="form-control select2" name="template" id="template">
        <option></option>
        @foreach(\App\EmailTemplate::limit(1000)->orderBy('name')->get() as $emailTemplate)
            <option value="{{ $emailTemplate->id }}">{{ $emailTemplate->name }}</option>
        @endforeach
    </select>
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

<div id="dateBox"  class="@if($formMode=='create') int_hide  @endif form-group {{ $errors->has('send_date') ? 'has-error' : ''}}">
    <label for="send_date" class="control-label">@lang('site.send-date')</label>
    <input class="form-control date" name="send_date" type="text" id="send_date" value="{{ old('send_date',isset($email->send_date) ? $email->send_date : '') }}" >
    {!! clean( $errors->first('send_date', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">@lang('site.attachments')</h5>
        <p class="card-text">

        <p>


        </p>





        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            @lang('site.resumes') @if($formMode=='edit') ({{ $email->candidates()->count() }})  @endif
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" >
                    <div class="card-body">

                        <div class="form-group {{ $errors->has('candidates') ? 'has-error' : ''}}">
                            <label for="candidates" class="control-label">@lang('site.candidates')</label>

                                 <?php
                                    $candidates = [];
                                    if($formMode=='edit'){
                                        foreach($email->candidates as $candidate){
                                            $candidates[] = $candidate->id;
                                        }
                                    }

                                    $list = old('candidates',$candidates);

                                    ?>

                            <select multiple  name="candidates[]" id="candidates" class="form-control">
                                @if(!empty($list) && is_array($list) )

                                    @foreach($list as $value)
                                        @if(\App\Candidate::find($value))
                                    <option selected value="{{ $value }}" >{{ \App\Candidate::find($value)->user->name }} ({{ \App\Candidate::find($value)->user->email }}) </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>

                            {!! clean( $errors->first('candidates', '<p class="help-block">:message</p>') ) !!}
                        </div>


                        <div class="form-group {{ $errors->has('profile_type') ? 'has-error' : ''}}">
                            <label for="profile_type" class="control-label">@lang('site.profile-type')</label>
                            <select name="profile_type" class="form-control" id="profile_type" >
                                @foreach (json_decode('{"p":"'.__('site.partial').'","f":"'.__('site.full').'"}', true) as $optionKey => $optionValue)
                                    <option value="{{ $optionKey }}" {{ ((null !== old('profile_type',@$emailprofile_type)) && old('profile_type',@$emailprofile_type) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                @endforeach
                            </select>
                            {!! clean( $errors->first('profile_type', '<p class="help-block">:message</p>') ) !!}
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            @lang('site.email-resources') @if($formMode=='edit') ({{ $email->emailResources()->count() }})  @endif
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" >
                    <div class="card-body">
                        <div class="form-group">
                            <label for="resources">@lang('site.resources')</label>

                                <select multiple name="resources[]" id="resources" class="form-control select2">
                                    <option></option>


                                    @if($formMode === 'edit')
                                        @foreach(\App\EmailResource::orderBy('name')->limit(1000)->get() as $resource)
                                            <option  @if( (is_array(old('resources')) && in_array(@$resource->id,old('resources')))  || (null === old('resources')  && $email->emailResources()->where('id',$resource->id)->first() ))
                                                selected
                                                @endif
                                                value="{{ $resource->id }}">{{ $resource->name }}</option>
                                        @endforeach
                                    @else

                                        @foreach(\App\EmailResource::orderBy('name')->limit(1000)->get() as $resource)
                                            <option @if(is_array(old('resources')) && in_array(@$resource->id,old('resources'))) selected @endif value="{{ $resource->id }}">{{ $resource->name }}</option>
                                        @endforeach

                                    @endif
                                </select>


                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            @lang('site.invoice')  @if($formMode=='edit') ({{ $email->invoices()->count() }})  @endif
                        </button>
                    </h2>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree">
                    <div class="card-body">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home">@lang('site.create-invoice')</a>
                            </li>
                            @if($formMode=='edit')
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu1">@lang('site.attached-invoices')</a>
                            </li>
                                @endif
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane container active int_pttwenty" id="home" >
                                <div class="form-check">
                                    <input id="attachCheckbox" autocomplete="off" class="form-check-input" type="checkbox" value="1" name="attach_invoice"  >
                                    <label class="form-check-label" for="attachCheckbox" >
                                        @lang('site.attach-invoice')
                                    </label>
                                </div>

                                <div id="invoiceFields" class="int_mtdispnone" >
                                <div class="form-group">
                                    <label for="title"><span class="req">*</span>@lang('site.item')</label>
                                    <input class="form-control" type="text" name="invoice_title"   />
                                </div>
                                <div class="form-group">
                                    <label for="amount"><span class="req">*</span>@lang('site.amount')</label>
                                    <input class="form-control digit" type="text" name="invoice_amount"  />
                                </div>
                                <div class="form-group">
                                    <label for="description">@lang('site.description')</label>
                                    <textarea class="form-control" name="invoice_description" id="description" ></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="paid"><span class="req">*</span>@lang('site.status')</label>
                                    <select class="form-control" name="invoice_paid" id="paid">
                                        <option value="0">@lang('site.unpaid')</option>
                                        <option value="1">@lang('site.paid')</option>
                                    </select>
                                </div>
                                <div class="form-group {{ $errors->has('invoice_category_id') ? 'has-error' : ''}}">
                                    <label for="invoice_category_id" class="control-label">@lang('site.invoice-category')</label>
                                    <select  class="form-control" name="invoice_category_id" id="invoice_category_id">
                                        <option value=""></option>
                                        @foreach(\App\InvoiceCategory::get() as $invoiceCategory)
                                            <option @if(old('invoice_category_id',isset($invoice->invoice_category_id) ? $invoice->invoice_category_id : '')==$invoiceCategory->id) selected @endif value="{{ $invoiceCategory->id }}">{{ $invoiceCategory->name }}</option>
                                        @endforeach
                                    </select>

                                    {!! clean( check( $errors->first('invoice_category_id', '<p class="help-block">:message</p>')) ) !!}
                                </div>
                            </div>
                            </div>
                            @if($formMode=='edit')
                            <div class="tab-pane container fade int_pttwenty" id="menu1" >

                                <div class="table-responsive" >
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('site.item')</th>
                                            <th>@lang('site.amount')</th>
                                            <th>@lang('site.status')</th>
                                            <th>@lang('site.created-on')</th>
                                            <th>@lang('site.actions')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($email->invoices()->latest()->get() as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->title }} </td>
                                                <td>{!! clean( check( price($item->amount)) ) !!}</td>
                                                <td>{{ ($item->paid==1)? __('site.paid'):__('site.unpaid') }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/M/Y') }}
                                                </td>
                                                <td>
                                                    @can('access','approve_invoice')
                                                    @if($item->paid==0)
                                                        <a onclick="return confirm('@lang('site.confirm-approve')')" class="btn btn-success btn-sm" href="{{ route('admin.invoices.approve',['invoice'=>$item->id]) }}"><i class="fa fa-thumbs-up"></i> @lang('site.approve')</a>
                                                    @endif
                                                    @endcan

                                                    @can('access','view_invoice')
                                                    <a target="_blank" href="{{ url('/admin/invoices/' . $item->id) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('site.view')</button></a>
                                                    @endcan
                                                    &nbsp;
                                                    @can('access','delete_invoice')


                                                    <a onclick="return confirm(&quot;@lang('site.confirm-delete')&quot;)"  class="btn btn-danger btn-sm"
                                                       href="{{ route('admin.invoices.delete',['id'=>$item->id]) }}?back=1">
                                                        <i class="fa fa-trash" aria-hidden="true"></i> @lang('site.delete')
                                                    </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                                @endif

                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingFour">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            @lang('site.upload-files')  @if($formMode=='edit') ({{ $email->emailAttachments()->count() }})  @endif
                        </button>
                    </h2>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="headingFour">
                    <div class="card-body">

                        @if($formMode=='edit' && $email->emailAttachments()->count() > 0)
                            <div  class="int_tpmb">
                                <p  >
                                    <span><i class="fa fa-paperclip"></i> {{ $email->emailAttachments()->count() }} @if($email->emailAttachments()->count()>1) @lang('site.attachments') @else @lang('site.attachment') @endif - </span>
                                    <a href="{{ route('admin.emails.download-attachments',['email'=>$email->id]) }}" class="btn btn-default btn-xs">@lang('site.download-all') <i class="fa fa-file-zip-o"></i> </a>
                                </p>

                                <div>
                                    <div class="row" >
                                        @foreach($email->emailAttachments as $attachment)
                                            <div class="col-md-4 int_mth"  >
                                                <a href="{{ route('admin.emails.download-attachment',['emailAttachment'=>$attachment->id]) }}">

                                                    <div class="card"  >


                                                        @if(isImage($attachment->file_path))
                                                            <img  src="{{ route('admin.image') }}?file={{ $attachment->file_path }}"  class="int_mh270 card-img-top" alt=""/>
                                                        @endif

                                                        <div class="card-body int_txcen" >
                                                            @if(!isImage($attachment->file_path))
                                                                <i class="int_fs200 fa fa-file text-info"></i>
                                                            @endif
                                                        </div>
                                                        <div class="card-footer">
                                                            {{ $attachment->file_name }}
                                                        </div>


                                                    </div>
                                                </a>
                                                <a  onclick="return confirm(&quot;@lang('site.confirm-delete')&quot;)"  class="int_mt10 btn btn-danger btn-block btn-sm" href="{{ route('admin.emails.delete-attachment',['emailAttachment'=>$attachment->id]) }}"><i class="fa fa-trash"></i> @lang('site.delete')</a>
                                            </div>

                                        @endforeach


                                    </div>

                                </div>
                            </div>
                        @endif

                        <div id="dropzone" class="dropmail int_tpmb" >

                            <div class="dropzone dropzone-custom needsclick" id="my-dropzone">
                                <div class="dz-message needsclick download-custom">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    <h1>@lang('site.attachments')</h1>
                                    <h2>@lang('site.upload-info')</h2>

                                </div>
                            </div>


                        </div>
                        <input type="hidden" name="msg_id" value="{{ $msgId }}"/>

                    </div>
                </div>
            </div>


        </div>





        </p>

    </div>

</div>
<br/>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>



