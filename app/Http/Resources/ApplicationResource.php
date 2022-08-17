<?php

namespace App\Http\Resources;

use App\Vacancy;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'user'=>new UserResource($this->user),
            'vacancy'=>new VacancyResource($this->vacancy),
            'shortlisted'=>$this->shortlisted
        ];
    }
}
