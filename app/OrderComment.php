<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderComment extends Model
{
    protected $fillable = ['order_id','user_id','content'];

    protected $touches = ['order'];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orderCommentAttachments(){
        return $this->hasMany(OrderCommentAttachment::class);
    }
}
