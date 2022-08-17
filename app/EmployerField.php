<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployerField extends Model
{
    protected $fillable = ['employer_field_group_id','name','type','sort_order','options','required','placeholder','enabled','filter'];

    public function employerFieldGroup(){
        return $this->belongsTo(EmployerFieldGroup::class);
    }

}
