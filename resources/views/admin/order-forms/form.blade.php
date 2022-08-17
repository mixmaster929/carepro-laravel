<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">@lang('site.name')</label>
    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($orderform->name) ? $orderform->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">@lang('site.description')</label>
    <textarea class="form-control" rows="5" name="description" type="textarea" id="description" >{{ old('description',isset($orderform->description) ? $orderform->description : '') }}</textarea>
    {!! clean( $errors->first('description', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('enabled') ? 'has-error' : ''}}">
    <label for="enabled" class="control-label">@lang('site.enabled')</label>
    <select name="enabled" class="form-control" id="enabled" >
    @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('enabled',@$orderform->enabled)) && old('enabled',@$orderform->enabled) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean( $errors->first('enabled', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('shortlist') ? 'has-error' : ''}}">
    <label for="shortlist" class="control-label">@lang('site.show-shortlist')</label>
    <select name="shortlist" class="form-control" id="shortlist" >
        @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ ((null !== old('shortlist',@$orderform->shortlist)) && old('shortlist',@$orderform->shortlist) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( $errors->first('shortlist', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('interview') ? 'has-error' : ''}}">
    <label for="interview" class="control-label">@lang('site.show-interview')</label>
    <select name="interview" class="form-control" id="interview" >
        @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ ((null !== old('interview',@$orderform->interview)) && old('interview',@$orderform->interview) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( $errors->first('interview', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('sort_order') ? 'has-error' : ''}}">
    <label for="sort_order" class="control-label">@lang('site.sort-order')</label>
    <input class="form-control number" name="sort_order" type="text" id="sort_order" value="{{ old('sort_order',isset($orderform->sort_order) ? $orderform->sort_order : '') }}" >
    {!! clean( $errors->first('sort_order', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group {{ $errors->has('auto_invoice') ? 'has-error' : ''}}">
    <label for="auto_invoice" class="control-label">@lang('site.enable-auto-invoice')</label>
    <select name="auto_invoice" class="form-control" id="auto_invoice" >
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ ((null !== old('auto_invoice',@$orderform->auto_invoice)) && old('auto_invoice',@$orderform->auto_invoice) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( $errors->first('auto_invoice', '<p class="help-block">:message</p>') ) !!}
</div>


<div id="invoice-group">


    <div class="form-group {{ $errors->has('invoice_amount') ? 'has-error' : ''}}">
        <label for="invoice_amount" class="control-label">@lang('site.invoice-amount')</label>
        <input class="form-control number" name="invoice_amount" type="text" id="invoice_amount" value="{{ old('invoice_amount',isset($orderform->invoice_amount) ? $orderform->invoice_amount : '') }}" >
        {!! clean( $errors->first('invoice_amount', '<p class="help-block">:message</p>') ) !!}
    </div>


    <div class="form-group {{ $errors->has('invoice_title') ? 'has-error' : ''}}">
        <label for="invoice_title" class="control-label">@lang('site.invoice-title')</label>
        <input class="form-control" name="invoice_title" type="text" id="invoice_title" value="{{ old('invoice_title',isset($orderform->invoice_title) ? $orderform->invoice_title : '') }}" >
        {!! clean( $errors->first('invoice_title', '<p class="help-block">:message</p>') ) !!}
    </div>



    <div class="form-group {{ $errors->has('invoice_description') ? 'has-error' : ''}}">
        <label for="invoice_description" class="control-label">@lang('site.invoice-description')</label>
        <textarea class="form-control" name="invoice_description" id="invoice_description"  rows="3">{{ old('invoice_description',isset($orderform->invoice_description) ? $orderform->invoice_description : '') }}</textarea>
        {!! clean( $errors->first('invoice_description', '<p class="help-block">:message</p>') ) !!}
    </div>




    <div class="form-group {{ $errors->has('invoice_category_id') ? 'has-error' : ''}}">
        <label for="invoice_category_id" class="control-label">@lang('site.invoice-category')</label>
        <select  class="form-control" name="invoice_category_id" id="invoice_category_id">
            <option value=""></option>
            @foreach(\App\InvoiceCategory::get() as $invoiceCategory)
                <option @if(old('invoice_category_id',isset($orderform->invoice_category_id) ? $orderform->invoice_category_id : '')==$invoiceCategory->id) selected @endif value="{{ $invoiceCategory->id }}">{{ $invoiceCategory->name }}</option>
            @endforeach
        </select>
    </div>



</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
@section('footer')
    @parent
    <script src="{{ asset('js/admin/order-form.js') }}"></script>

    @endsection
