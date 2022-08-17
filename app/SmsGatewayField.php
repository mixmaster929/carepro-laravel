<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsGatewayField extends Model
{
   // protected $fillable = ['sms_gateway_id','key','value','serialized','type','options','class','sort_order'];
    protected $guarded = ['id'];
    public function smsGateway(){
        return $this->belongsTo(SmsGateway::class);
    }

}
