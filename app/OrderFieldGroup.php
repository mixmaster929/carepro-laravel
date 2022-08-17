<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderFieldGroup extends Model
{
    protected $fillable = ['name','sort_order','description','order_form_id','layout','columns'];

    public function orderFields(){
        return $this->hasMany(OrderField::class);
    }

    public function orderForm(){
        return $this->belongsTo(OrderForm::class);
    }

}
