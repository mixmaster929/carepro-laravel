<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="control-label required">@lang('site.user')</label>

    <select required  name="user_id" id="user_id" class="form-control">
        @php
        $userId = old('user_id',isset($invoice->user_id) ? $invoice->user_id : '');
        @endphp

        @if($userId)
            <option selected value="{{ $userId }}">{{ \App\User::find($userId)->name }} ({{ \App\User::find($userId)->email }}) </option>
        @endif
    </select>

    {!! clean( check( $errors->first('user_id', '<p class="help-block">:message</p>')) ) !!}
</div>

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label required">@lang('site.title')</label>
    <input required class="form-control" name="title" type="text" id="title" value="{{ old('title',isset($invoice->title) ? $invoice->title : '') }}" >
    {!! clean( check( $errors->first('title', '<p class="help-block">:message</p>')) ) !!}
</div>


<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">@lang('site.description')</label>
     <textarea class="form-control" name="description" id="description" >{{ old('description',isset($invoice->description) ? $invoice->description : '') }}</textarea>
    {!! clean( check( $errors->first('description', '<p class="help-block">:message</p>')) ) !!}
</div>



<div class="form-group {{ $errors->has('amount') ? 'has-error' : ''}}">
    <label for="amount" class="control-label required">@lang('site.amount')</label>
    <input required class="form-control digit" name="amount" type="text" id="amount" value="{{ old('amount',isset($invoice->amount) ? $invoice->amount : '') }}" >
    {!! clean( check( $errors->first('amount', '<p class="help-block">:message</p>')) ) !!}
</div>




<div class="form-group {{ $errors->has('paid') ? 'has-error' : ''}}">
    <label for="paid" class="control-label required">@lang('site.paid')</label>
    <select required name="paid" class="form-control" id="paid" >
    @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('paid',@$invoice->paid)) && old('invoice',@$invoice->paid) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean( check( $errors->first('paid', '<p class="help-block">:message</p>')) ) !!}
</div>

<div class="form-group {{ $errors->has('payment_method_id') ? 'has-error' : ''}}">
    <label for="payment_method_id" class="control-label">@lang('site.payment-method')</label>
    <select  class="form-control" name="payment_method_id" id="payment_method_id">
        <option value=""></option>
        @foreach(\App\PaymentMethod::get() as $paymentMethod)
            <option @if(old('payment_method_id',isset($invoice->payment_method_id) ? $invoice->payment_method_id : '')==$paymentMethod->id) selected @endif value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
        @endforeach
    </select>

    {!! clean( check( $errors->first('payment_method_id', '<p class="help-block">:message</p>')) ) !!}
</div>



<div class="form-group {{ $errors->has('due_date') ? 'has-error' : ''}}">
    <label for="due_date" class="control-label required">@lang('site.due-date')</label>
    <input required class="form-control date" name="due_date" type="text" id="due_date" value="{{ old('due_date',isset($invoice->due_date) ? $invoice->due_date : \Illuminate\Support\Carbon::now()->toDateString()) }}" >
    {!! clean( check( $errors->first('due_date', '<p class="help-block">:message</p>')) ) !!}
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

@if($formMode=='create')
    <div class="row">
        <div class="col-md-6">
            <div class="form-check">
                <input class="form-check-input" checked  type="checkbox" value="1" id="notify" name="notify">
                <label class="form-check-label" for="notify">
                    @lang('site.notify-user')
                </label>
            </div>
        </div>
    </div>
    <br/>
@endif

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
