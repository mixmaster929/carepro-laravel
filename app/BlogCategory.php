<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = ['name','sort_order'];

    public function blogPosts(){
        return $this->belongsToMany(BlogPost::class);
    }
}
