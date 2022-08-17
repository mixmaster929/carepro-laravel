<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceCategory extends Model
{
    protected $fillable = ['name','sort_order'];

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }


}
