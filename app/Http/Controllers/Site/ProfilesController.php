<?php

namespace App\Http\Controllers\Site;

use App\Candidate;
use App\CandidateField;
use App\CandidateFieldGroup;
use App\Category;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
use HelperTrait;

    public function index(Request $request){
        $keyword = $request->get('search');
        $perPage = 24;

        $params = $request->all();

        if(!empty($keyword)){
            $candidates = User::where('role_id',3);
        }
        else{
            $candidates = User::where('role_id',3)->latest();
        }

        $candidates = $candidates->whereHas('candidate',function($query) use ($keyword){
           $query->where('public',1);
            if(!empty($keyword)){
                $query->whereRaw("match(display_name) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            }

        });

        if(isset($params['category']) && $params['category'] != ''){
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use($params) {
                $query->whereHas('categories',function (Builder $query) use($params){
                    $query->where('id',$params['category']);
                });
            });
        }

        if(isset($params['gender']) && $params['gender'] != ''){
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use($params) {
                $query->where('gender',$params['gender']);
            });
        }

        if(isset($params['min_age'])){
            $year = date('Y') - $params['min_age'];
            $minDate = $year.'-12-31';
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use ($minDate) {
                $query->where('date_of_birth','<=',$minDate);
            });
        }

        if(isset($params['max_age'])){
            $year = date('Y') - $params['max_age'];
            $maxDate = $year.'-01-01';
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use ($maxDate) {
                $query->where('date_of_birth','>=',$maxDate);
            });
        }

        //get fields for filter
        $fields = CandidateField::where('filter',1)->orderBy('sort_order')->get();

        foreach($fields as $field){
            if(isset($params['field_'.$field->id]) && !empty($params['field_'.$field->id]))
            {
                $value = $params['field_'.$field->id];
                $candidates = $candidates->whereHas('candidateFields',function(Builder $query) use ($value) {
                        $query->whereRaw("match(value) against (? IN NATURAL LANGUAGE MODE)", [$value]);
                    });
            }
        }


        $candidates = $candidates->paginate($perPage);

        $title = __('site.candidate-profiles');
        if($request->has('category') && Category::find($request->category)){
            $title .= ': '.Category::find($request->category)->name;
        }

        //get Categories
        $categories = Category::where('public',1)->orderBy('sort_order')->get();

        $params = compact('candidates','fields','title','categories');
        if (isEmployer()){
            return view('site.profiles.index',$params);
        }

        return tview('site.profiles.index',$params);
    }

    public function profile(Candidate $candidate){
        if(empty($candidate->public)){
            return abort(404);
        }

        //get field groups
        $groups = CandidateFieldGroup::where('visible',1)->orderBy('sort_order')->get();

        if (isEmployer()){
            return view('site.profiles.profile',compact('candidate','groups'));
        }

        return tview('site.profiles.profile',compact('candidate','groups'));
    }

    public function shortlistCandidate(Candidate $candidate){
        if(empty($candidate->public)){
            return abort(404);
        }

        $cart = session()->get('cart');
        if(!$cart){
            $cart = [];
        }

        $cart[$candidate->id] = $candidate->id;
        session()->put('cart',$cart);
        return redirect()->route('shortlist')->with('candidate',$candidate->id);

    }

    public function shortlist(){
        $cart = session()->get('cart');
        if(isEmployer()){
            return view('site.profiles.shortlist',compact('cart'));
        }
        return tview('site.profiles.shortlist',compact('cart'));
    }

    public function removeFromList(Candidate $candidate){
        $cart = session()->get('cart');
        if(!$cart){
            $cart = [];
        }

        unset($cart[$candidate->id]);
        $this->successMessage(__('site.profile-deleted'));
        session()->put('cart',$cart);
        return redirect()->route('shortlist');
    }

}
