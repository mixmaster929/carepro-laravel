<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = ['name','description','status','minutes','allow_multiple','passmark','private','show_result'];

    public function testQuestions(){
        return $this->hasMany(TestQuestion::class);
    }

    public function userTests(){
        return $this->hasMany(UserTest::class);
    }

    public function setDescription($value){
        $this->attributes['description'] = saveInlineImages($value);
    }

}
