<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['region_id', 'user_id','interview_date','status','interview_location','interview_time','comments','order_form_id'];

    public function orderFields(){
        return $this->belongsToMany(OrderField::class)->withPivot(['value']);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function candidates(){
        return $this->belongsToMany(Candidate::class);
    }

    public function invoices(){
        return $this->belongsToMany(Invoice::class);
    }

    public function orderComments(){
        return $this->hasMany(OrderComment::class);
    }

    public function orderForm(){
        return $this->belongsTo(OrderForm::class);
    }

    public function jobRegion(){
        return $this->belongsTo(JobRegion::class, 'region_id');
    }

    public function bids(){
        return $this->belongsToMany(User::class)->withPivot('offer', 'status');
    }
}
