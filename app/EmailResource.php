<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailResource extends Model
{
    protected $fillable = ['name','file_name','file_path'];
}
