<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user();
        return [
            'created_at'=>$this->created_at,
            'id'=>$this->id,
            'name'=>$this->name,
            //'content'=>$this->content,
            'enabled'=>$this->enabled,
            'description'=>$this->description,
            'users'=> UserResource::collection($this->users),
            'signed'=>$this->users()->where('id',$user->id)->first()->pivot->signed==1
        ];

    }
}
