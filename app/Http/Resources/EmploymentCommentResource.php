<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentCommentResource extends JsonResource
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
            'content'=>$this->content,
            'attachments'=> EmploymentCommentAttachmentResource::collection($this->employmentCommentAttachments)
        ];
    }
}
