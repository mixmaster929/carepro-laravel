<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = ['title','content','cover_photo','publish_date','status','meta_title','meta_description','user_id'];

    public function blogCategories(){
        return $this->belongsToMany(BlogCategory::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
