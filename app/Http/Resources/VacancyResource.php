<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'title'=>$this->title,
            'description'=>$this->description,
            'closes_at'=>$this->closes_at,
            'active'=>$this->active,
            'location'=>$this->location,
            'salary'=>$this->salary,
            'categories'=> JobCategoryResource::collection($this->jobCategories)
        ];
    }
}
