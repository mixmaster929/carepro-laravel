<?php

namespace App\Http\Resources;

use App\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CandidateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $categories = Category::where('public',1)->orderBy('sort_order')->get();
        return [
            'data' => CandidateResource::collection($this->collection),
            'categories' => CategoryResource::collection($categories)
        ];
    }
}
