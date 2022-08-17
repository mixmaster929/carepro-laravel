<?php

namespace App\Http\Resources;

use App\InvoiceCategory;
use App\PaymentMethod;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'id'=>$this->id,
            'amount'=>$this->amount,
            'payment_method'=> $this->paymentMethod? new PaymentMethodResource($this->paymentMethod):null,
            'title'=>$this->title,
            'description'=>$this->description,
            'paid'=>$this->paid,
            'due_date'=>$this->due_date,
            'invoice_category'=> $this->invoiceCategory ? new InvoiceCategoryResource($this->invoiceCategory):null,
            'has_orders'=> $this->orders()->exists(),
            'order' => $this->orders()->exists() ? $this->orders()->first()->id : null,
            'hash'=>$this->hash,
            'user'=>new UserResource($this->user)
        ];
    }
}
