<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployerFieldGroup extends Model
{
    protected $fillable = ['name','sort_order','registration','public'];

    public function employerFields(){
        return $this->hasMany(EmployerField::class);
    }

}
