<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable= ['name','status','code','sort_order','method_label','settings'];

    public function paymentMethodFields(){
        return $this->hasMany(PaymentMethodField::class);
    }

}
