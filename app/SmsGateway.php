<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsGateway extends Model
{
    protected $fillable = ['gateway_name','url','code','active','settings'];

    public function smsGatewayFields(){
        return $this->hasMany(SmsGatewayField::class);
    }
}
