<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable= ['name','message'];
}
