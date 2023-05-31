<?php

namespace App\Http\Controllers\Employer;

use App\Employer;
use App\EmployerField;
use App\EmployerFieldGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Application;
use App\Candidate;
use App\CandidateFieldGroup;
use App\UserTest;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class ProfileController extends Controller
{

    public function profile(){
        $employer = Auth::user();
        $kvk_flag = false;
        $niwo_flag = false;
        $KvK = $employer->employerFields()->where('name', 'KvK nummer')->first()? $employer->employerFields()->where('name', 'KvK nummer')->first()->pivot->value : "";
        // dd($KvK);
        // $niwo = $employer->employerFields()->where('name','Eurovergunningsnummer')->first()? $candidate->candidateFields()->where('name','Eurovergunningsnummer')->first()->pivot->value : "";
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
        if (!empty($niwo) && is_numeric($niwo)) {
            $niwo_flag = true;
        }
        return view('employer.profile.profile', compact('employer', 'kvk_flag', 'paychecked_flag', 'niwo_flag'));
    }

    public function update(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required|email|string|max:255',
        ];

        $user = Auth::user();
        if($user->email != $request->email){
            $rules['email'] = 'required|email|string|max:255|unique:users';
        }
        foreach(EmployerFieldGroup::where('public',1)->orderBy('sort_order')->get() as $group) {
            foreach ($group->employerFields as $field) {

                if ($field->type == 'file') {
                    $required = '';
                    if ($field->required == 1) {
                        $required = 'required|';
                    }

                    $rules['field_' . $field->id] = 'nullable|' . $required . 'max:' . config('app.upload_size') . '|mimes:' . config('app.upload_files');
                } elseif ($field->required == 1) {
                    $rules['field_' . $field->id] = 'required';
                }
            }

        }


        $this->validate($request,$rules);

        $requestData = $request->all();

        $user->update($requestData);


        //checkfor picture

        $employerRecord = Employer::where('user_id',$user->id)->first();
        $employerRecord->update($requestData);
        //$user->employer()->update($requestData);


        //now save custom fields
        $fields = EmployerField::get();

        $customValues = [];
        //attach custom values
        foreach(EmployerFieldGroup::where('public',1)->orderBy('sort_order')->get() as $group) {
            foreach ($group->employerFields as $field) {

                if ($field->type == 'file') {
                    if ($request->hasFile('field_' . $field->id)) {
                        //get current file name
                        if ($user->employerFields()->where('id', $field->id)->first()) {
                            $fileName = $user->employerFields()->where('id', $field->id)->first()->pivot->value;
                            @unlink($fileName);
                        }

                        //generate name for file

                        $name = $_FILES['field_' . $field->id]['name'];

                        //dd($name);


                        $extension = $request->{'field_' . $field->id}->extension();
                        //  dd($extension);

                        $name = str_ireplace('.' . $extension, '', $name);

                        $name = $user->id . '_' . time() . '_' . safeUrl($name) . '.' . $extension;

                        $path = $request->file('field_' . $field->id)->storeAs(EMPLOYER_FILES, $name, 'public_uploads');


                        $file = UPLOAD_PATH . '/' . $path;
                        $customValues[$field->id] = ['value' => $file];
                    } elseif ($user->employerFields()->where('id', $field->id)->first()) {
                        $fileName = $user->employerFields()->where('id', $field->id)->first()->pivot->value;
                        $customValues[$field->id] = ['value' => $fileName];
                    }
                } else {
                    $customValues[$field->id] = ['value' => $requestData['field_' . $field->id]];
                }


            }
        }

        $user->employerFields()->sync($customValues);

        return back()->with('flash_message',__('site.changes-saved'));

    }

    public function showProfile(Candidate $candidate, Application $application){
        if(empty($candidate->public) && ($application->user_id != $candidate->user_id)){
            return abort(404);
        }

        //get field groups
        $groups = CandidateFieldGroup::where('visible',1)->orderBy('sort_order')->get();

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

    public function showProfileTest(Candidate $candidate, UserTest $userTest){
        if(empty($candidate->public) && ($userTest->user_id != $candidate->user_id)){
            return abort(404);
        }

        //get field groups
        $groups = CandidateFieldGroup::where('visible',1)->orderBy('sort_order')->get();

        if (isEmployer()){
            return view('site.profiles.profile',compact('candidate','groups'));
        }

        return tview('site.profiles.profile',compact('candidate','groups'));
    }
}
