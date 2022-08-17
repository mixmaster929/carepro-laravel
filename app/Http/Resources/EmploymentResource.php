<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentResource extends JsonResource
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
            'employer'=>new EmployerResource($this->employer),
            'candidate'=>new CandidateResource($this->candidate),
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'active'=>$this->active,
            'salary'=>$this->salary,
            'salary_type'=>$this->salary_type,
            'employment_comments'=>EmploymentCommentResource::collection($this->employmentComments)
        ];
    }
}
