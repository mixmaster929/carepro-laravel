<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
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
            'interview_date'=>$this->interview_date,
            'venue'=>$this->venue,
            'internal_note'=>$this->internal_note,
            'employer_comment'=>$this->employer_comment,
            'reminder'=>$this->reminder,
            'feedback'=>$this->feedback,
            'interview_time'=>$this->interview_time,
            'employer_feedback'=>$this->employer_feedback,
            'candidates'=>CandidateResource::collection($this->candidates)
        ];
    }
}
