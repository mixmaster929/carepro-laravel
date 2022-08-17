<?php

namespace App\Http\Resources;

use App\InvoiceCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $invoiceCategory = InvoiceCategory::find($this->invoice_category_id);

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'enabled'=>$this->enabled,
            'shortlist'=>$this->shortlist,
            'interview'=>$this->interview,
            'sort_order'=>$this->sort_order,
            'auto_invoice'=>$this->auto_invoice,
            'invoice_amount'=>$this->invoice_amount,
            'invoice_title'=>$this->invoice_title,
            'invoice_description'=>$this->invoice_description,
            'invoice_category'=> ($invoiceCategory) ? new InvoiceCategoryResource($invoiceCategory):null,
            'groups'=> OrderFieldGroupResource::collection($this->orderFieldGroups)
        ];
    }
}
