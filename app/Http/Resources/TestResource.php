<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);
        $user = $request->user();
        if($user){
            $userTest  = $user->userTests()->where('test_id',$this->id)->first();
            $array['has_test'] = $userTest ? true:false;
        }
        else{
            $array['has_test'] = false;
        }

        $array ['questions'] = TestQuestionResource::collection($this->testQuestions);

        return $array;
    }
}
