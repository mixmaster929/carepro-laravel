      <select  class="form-control" name="{{ $setting->key }}" id="{{ $setting->key }}">
        <option value=""></option>
        @foreach(\App\InvoiceCategory::get() as $invoiceCategory)
            <option @if($setting->value==$invoiceCategory->id) selected @endif value="{{ $invoiceCategory->id }}">{{ $invoiceCategory->name }}</option>
        @endforeach
    </select>

