<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodField extends Model
{
    protected $fillable = ['key','value','serialized','type','options','class','payment_method_id'];

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }
}
