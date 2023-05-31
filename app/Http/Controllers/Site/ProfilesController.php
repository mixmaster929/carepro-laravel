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
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

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
        // dd($candidate);
        // added kvk
        $user = User::find($candidate->user_id);
        // dd($candidate);
        $kvk_flag = false;
        $niwo_flag = false;
        $KvK = $user->candidateFields()->where('name','KvK Handelsregister')->first()? $user->candidateFields()->where('name','KvK Handelsregister')->first()->pivot->value : "";
        $niwo = $user->candidateFields()->where('name','Eurovergunningsnummer')->first()? $user->candidateFields()->where('name','Eurovergunningsnummer')->first()->pivot->value : "";
        $apiKey = "l7194f0c28d6844efd8d4ae8ea83604836";
        $prodKvKApi = "https://api.kvk.nl/api/v1/zoeken?";
        $prodPayCheckedApi = "https://www.paychecked.nl/register/?Bedrijfsnaam=&Bedrijfsplaats=&KvK=";
        
        // PayChecked
        // $crawler = new Client();
        $crawler = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $crawler = $crawler->request('GET', $prodPayCheckedApi.$KvK);
        $result = NULL;

        $paychecked_flag = false;
        $crawler->filter('.total__header')->each(function ($node) use (&$result) {
            $result = $node->text();
        });
        // dd($result);
        if($result != "Aantal bedrijven: 0")
            $paychecked_flag = true;

        // KVK
        if($KvK){
            // $response = Http::get($prodKvKApi."apikey=".$apiKey."&kvkNummer=".$KvK);
            // if($response->status() == 200)
            // $kvk_flag = true;

            $url = $prodKvKApi."apikey=".$apiKey."&kvkNummer=".$KvK;

            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CAINFO, 'F:/cert/cacert.pem');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $data = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if(curl_errno($ch)) {
                $kvk_flag = false;
            } else {
                if($httpCode == 200)
                $kvk_flag = true;
                else
                $kvk_flag = false;
            }

            curl_close ($ch);
        }
        
        // Niwo
        if($niwo){
            $niwo_flag = true;
        }

        if (isEmployer()){
            return view('site.profiles.profile',compact('candidate','groups', 'kvk_flag', 'paychecked_flag', 'niwo_flag'));
        }

        return tview('site.profiles.profile',compact('candidate','groups', 'kvk_flag', 'paychecked_flag', 'niwo_flag'));
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
