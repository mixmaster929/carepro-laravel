<?php

namespace App\Http\Resources;

use App\OrderFieldGroup;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data= [
            'id'=>$this->id,
            'user'=> new UserResource($this->user),
            'interview_date'=> $this->interview_date,
            'status'=>$this->status,
            'interview_location'=>$this->interview_location,
            'interview_time'=>$this->interview_time,
            'comments'=>$this->comments,
            'order_form'=> new OrderFormResource($this->orderForm),
            'order_comments'=>OrderCommentResource::collection($this->orderComments),
            'candidates'=>CandidateResource::collection($this->candidates),
            'invoices'=>InvoiceResource::collection($this->invoices),
            'created_at'=>$this->created_at,
            'update_at'=>$this->updated_at

        ];

        $data['custom'] = [];

        //get field groups
        $groups = OrderFieldGroup::where('order_form_id',$this->id)->orderBy('sort_order')->get();
        foreach ($groups as $group){

            $data['custom'][$group->id]['group_name'] = $group->name;
            $data['custom'][$group->id]['group_id'] = $group->id;
            //group data

            foreach ($group->orderFields()->orderBy('sort_order')->get() as $field){
                $value = ($this->orderFields()->where('id',$field->id)->first()) ? $this->orderFields()->where('id',$field->id)->first()->pivot->value:'';

                /*  if($field->type=='file' && !empty($value)){
                      $value = asset($value);
                  }*/

                $data['custom'][$group->id]['data'][$field->id] = [
                    'field_id'=>$field->id,
                    'field_name'=>$field->name,
                    'field_type'=>$field->type,
                    'value'=>$value
                ];
            }

        }

        return $data;
    }
}
