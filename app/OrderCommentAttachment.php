<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCommentAttachment extends Model
{
    protected $fillable = ['file_name','file_path','order_comment_id'];

    public function orderComment(){
        return $this->belongsTo(OrderComment::class);
    }

}
