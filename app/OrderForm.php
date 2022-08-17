<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderForm extends Model
{
    protected $fillable = ['name','description','enabled','shortlist','interview','sort_order','auto_invoice','invoice_amount','invoice_title','invoice_description','invoice_category_id'];

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function orderFieldGroups(){
        return $this->hasMany(OrderFieldGroup::class);
    }

    public function orderFields(){
        return $this->hasManyThrough(OrderField::class,OrderFieldGroup::class);
    }

    public function invoiceCategory(){
        return $this->belongsTo(InvoiceCategory::class);
    }

}
