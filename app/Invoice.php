<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['user_id','amount','payment_method_id','title','description','paid','due_date','invoice_category_id','hash'];

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function invoiceCategory(){
        return $this->belongsTo(InvoiceCategory::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }
}
