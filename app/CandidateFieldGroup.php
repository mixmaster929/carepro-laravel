<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateFieldGroup extends Model
{
    protected $fillable = ['name','sort_order','public','registration','visible'];


    public function candidateFields(){
        return $this->hasMany(CandidateField::class);
    }


}
