<?php

namespace App\Http\Resources;

use App\JobCategory;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VacancyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $categories = JobCategory::orderBy('sort_order')->get();
        return [
            'data' => VacancyResource::collection($this->collection),
            'categories' => JobCategoryResource::collection($categories)
        ];
    }
}
