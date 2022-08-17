<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderField extends Model
{
    protected $fillable = ['order_field_group_id','name','type','sort_order','options','required','placeholder','enabled','filter'];

    public function orderFieldGroup(){
        return $this->belongsTo(OrderFieldGroup::class);
    }

}
