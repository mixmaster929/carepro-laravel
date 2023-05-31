<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scrapy extends Model
{
    use HasFactory;

    protected $fillable = [
        'url','name','number','address', 'city', 'kvk'
     ];
 
}
