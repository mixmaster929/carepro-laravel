<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'=>$this->name,
            'email'=>$this->email,
            'api_token'=>$this->api_token,
            'role_id'=>$this->role_id,
            'role'=>$this->role->name,
            'status'=>$this->status,
            'telephone'=>$this->telephone,
            'picture'=>(isset($this->candidate->picture) && !empty($this->candidate->picture))? asset($this->candidate->picture) : asset('img/man.jpg')
        ];
    }
}
