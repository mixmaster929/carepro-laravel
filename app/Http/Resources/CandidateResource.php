<?php

namespace App\Http\Resources;

use App\CandidateFieldGroup;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $data = [
            'id'=>$this->id,
            'user'=> new UserResource($this->user),
            'display_name'=>$this->display_name,
            'date_of_birth'=>$this->date_of_birth,
            'age'=>getAge(Carbon::parse($this->date_of_birth)->timestamp),
            'gender'=>$this->gender,
            'picture'=> !empty($this->picture)? asset($this->picture):'',
            'employed'=>boolToString($this->employed),
            'video_code'=>$this->video_code,
            'categories'=> isset($this->categories)?CategoryResource::collection($this->categories()->get()):[],
            'cv_path'=>(!empty($this->cv_path) && file_exists($this->cv_path))? $this->cv_path:'',
        ];

        $data['custom'] = [];

        //get field groups
        $groups = CandidateFieldGroup::where('public',1)->orderBy('sort_order')->get();
        foreach ($groups as $group){

            $data['custom'][$group->id]['group_name'] = $group->name;
            $data['custom'][$group->id]['group_id'] = $group->id;
            //group data

            foreach ($group->candidateFields()->orderBy('sort_order')->get() as $field){
                $value = ($this->user->candidateFields()->where('id',$field->id)->first()) ? $this->user->candidateFields()->where('id',$field->id)->first()->pivot->value:'';

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
