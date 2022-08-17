<?php

namespace App\Http\Resources;

use App\EmployerFieldGroup;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerResource extends JsonResource
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
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'user'=> new UserResource($this->user),
            'active'=> $this->active,
            'gender' => $this->gender
        ];

        $data['custom'] = [];

        //get field groups
        $groups = EmployerFieldGroup::where('public',1)->orderBy('sort_order')->get();
        foreach ($groups as $group){

            $data['custom'][$group->id]['group_name'] = $group->name;
            $data['custom'][$group->id]['group_id'] = $group->id;
            //group data

            foreach ($group->employerFields()->orderBy('sort_order')->get() as $field){
                $value = ($this->user->employerFields()->where('id',$field->id)->first()) ? $this->user->employerFields()->where('id',$field->id)->first()->pivot->value:'';

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
