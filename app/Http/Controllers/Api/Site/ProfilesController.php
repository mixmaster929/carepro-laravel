<?php

namespace App\Http\Controllers\Api\Site;

use App\Candidate;
use App\CandidateField;
use App\CandidateFieldGroup;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateCollection;
use App\Http\Resources\CandidateFieldResource;
use App\Http\Resources\CandidateResource;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{

    public function candidateFields(){
        $fields = CandidateField::where('filter',1)->orderBy('sort_order')->get();
        return CandidateFieldResource::collection($fields);
    }

    public function profiles(Request $request){
        $keyword = $request->get('search');


        $perPage = 24;
        if($request->has('per_page')){
            $perPage = $request->per_page;
        }

        $params = $request->all();

        if(!empty($keyword)){
            $candidates = Candidate::query();
        }
        else{
            $candidates = Candidate::latest();
        }


            $candidates->where('public',1);
            if(!empty($keyword)){
                $candidates->whereRaw("match(display_name) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            }



        if(isset($params['category']) && $params['category'] != ''){

            $candidates->whereHas('categories',function (Builder $query) use($params){
                    $query->where('id',$params['category']);
                });

        }

        if(isset($params['gender']) && $params['gender'] != ''){

            $candidates->where('gender',$params['gender']);

        }

        if(isset($params['min_age'])){
            $year = date('Y') - $params['min_age'];
            $minDate = $year.'-12-31';

            $candidates->where('date_of_birth','<=',$minDate);

        }

        if(isset($params['max_age'])){
            $year = date('Y') - $params['max_age'];
            $maxDate = $year.'-01-01';

            $candidates->where('date_of_birth','>=',$maxDate);

        }

        //get fields for filter
        $fields = CandidateField::where('filter',1)->orderBy('sort_order')->get();

        foreach($fields as $field){
            if(isset($params['field_'.$field->id]) && !empty($params['field_'.$field->id]))
            {
                $value = $params['field_'.$field->id];

                $candidates->whereHas('user',function (Builder $query) use($value){
                    $query->whereHas('candidateFields',function(Builder $query) use ($value) {
                        $query->whereRaw("match(value) against (? IN NATURAL LANGUAGE MODE)", [$value]);
                    });
                });

            }
        }


        $candidates = $candidates->paginate($perPage);


        return new CandidateCollection($candidates);

    }

    public function profile(Candidate $candidate){
        if(empty($candidate->public)){
            return abort(404);
        }

        return new CandidateResource($candidate);
    }

}
