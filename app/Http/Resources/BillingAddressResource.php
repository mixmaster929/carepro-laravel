<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillingAddressResource extends JsonResource
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
            'address'=>$this->address,
            'address_2'=>$this->address_2,
            'city'=>$this->city,
            'state'=>$this->state,
            'zip'=>$this->zip,
            'country'=> new CountryResource($this->country),
            'is_default'=>$this->is_default,
            'phone'=>$this->phone
        ];
    }
}
