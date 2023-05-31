<?php

namespace App\Http\Controllers\Candidate;

use App\Candidate;
use App\CandidateField;
use App\CandidateFieldGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use App\User;

class ProfileController extends Controller
{
    public function profile(){
        $candidate = Auth::user();
        $user = User::find($candidate->id);
        $kvk_flag = false;
        $niwo_flag = false;
        $KvK = $user->candidateFields()->where('name','KvK Handelsregister')->first()? $user->candidateFields()->where('name','KvK Handelsregister')->first()->pivot->value : "";
        $niwo = $user->candidateFields()->where('name','Eurovergunningsnummer')->first()? $user->candidateFields()->where('name','Eurovergunningsnummer')->first()->pivot->value : "";
        $apiKey = "l7194f0c28d6844efd8d4ae8ea83604836";
        $prodKvKApi = "https://api.kvk.nl/api/v1/zoeken?";
        $prodPayCheckedApi = "https://www.paychecked.nl/register/?Bedrijfsnaam=&Bedrijfsplaats=&KvK=";
        
        // PayChecked
        $crawler = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
    
        $crawler = $crawler->request('GET', $prodPayCheckedApi.$KvK);
        $result = NULL;
    
        $paychecked_flag = false;
        $crawler->filter('.total__header')->each(function ($node) use (&$result) {
            $result = $node->text();
        });
    
        if($result != "Aantal bedrijven: 0")
            $paychecked_flag = true;
    
        // KVK
        if($KvK){
            $url = $prodKvKApi."apikey=".$apiKey."&kvkNummer=".$KvK;
            $ch = curl_init();
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
        if($candidate->candidate->locked==1){
            return back()->with('flash_message',__('site.profile-locked'));
        }
        return view('candidate.profile.profile', compact('candidate', 'kvk_flag', 'paychecked_flag', 'niwo_flag'));
    }

    public function update(Request $request){
        $user = Auth::user();
        if($user->candidate->locked==1){
            return back()->with('flash_message',__('site.profile-locked'));
        }

        $rules = [
            'name'=>'required',
            'display_name'=>'required',
            'gender'=>'required',
            'email'=>'required|email|string|max:255',
            'date_of_birth_year'=>'required',
            'date_of_birth_month'=>'required',
            'date_of_birth_day'=>'required',
            'picture' => 'nullable|max:'.config('app.upload_size').'|mimes:jpeg,png,gif',
            'cv_path' => 'nullable|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files'),
        ];

        if($user->email != $request->email){
            $rules['email'] = 'required|email|string|max:255|unique:users';
        }


        foreach(CandidateFieldGroup::where('public',1)->orderBy('sort_order')->get() as $group){

            foreach($group->candidateFields as $field){

                if($field->type=='file'){
                    $required = '';
                    if($field->required==1){
                        $required = 'required|';
                    }

                    $rules['field_'.$field->id] = 'nullable|'.$required.'max:'.config('app.upload_size').'|mimes:'.config('app.upload_files');
                }
                elseif($field->required==1){
                    $rules['field_'.$field->id] = 'required';
                }
            }

        }




        $this->validate($request,$rules);

        $requestData = $request->all();


        $user->update($requestData);

        //save categories
        $user->candidate->categories()->sync($request->categories);

        $dateOfBirth = $request->date_of_birth_year.'-'.$request->date_of_birth_month.'-'.$request->date_of_birth_day;
        $requestData['date_of_birth'] = $dateOfBirth;

        //checkfor picture
        if($request->hasFile('picture')) {
            @unlink($user->candidate->picture);
            $path =  $request->file('picture')->store(CANDIDATES,'public_uploads');

            $file = UPLOAD_PATH.'/'.$path;
            $img = Image::make($file);

            $img->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($file);

            $requestData['picture'] = $file;
        }
        else{

            $requestData['picture'] = $user->candidate->picture;
        }

        if($request->hasFile('cv_path')) {
            @unlink($user->candidate->cv_path);
            $name = $_FILES['cv_path']['name'];

            $extension = $request->cv_path->extension();
            //  dd($extension);

            $name = str_ireplace('.'.$extension,'',$name);

            $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

            $path =  $request->file('cv_path')->storeAs(CANDIDATE_FILES,$name,'public_uploads');

            $file = UPLOAD_PATH.'/'.$path;

            $requestData['cv_path'] = $file;
        }
        else{

            $requestData['cv_path'] = $user->candidate->cv_path;
        }

        $candidateRecord = Candidate::where('user_id',$user->id)->first();
        $candidateRecord->changeUserFillable();
        $candidateRecord->update($requestData);
        //$user->candidate()->update($requestData);


        //now save custom fields
        $fields = CandidateField::get();

        $customValues = [];
        //attach custom values


        foreach(CandidateFieldGroup::where('public',1)->orderBy('sort_order')->get() as $group) {

            foreach ($group->candidateFields as $field) {

                if ($field->type == 'file') {
                    if ($request->hasFile('field_' . $field->id)) {
                        //get current file name
                        if ($user->candidateFields()->where('id', $field->id)->first()) {
                            $fileName = $user->candidateFields()->where('id', $field->id)->first()->pivot->value;
                            @unlink($fileName);
                        }

                        //generate name for file

                        $name = $_FILES['field_' . $field->id]['name'];

                        //dd($name);


                        $extension = $request->{'field_' . $field->id}->extension();
                        //  dd($extension);

                        $name = str_ireplace('.' . $extension, '', $name);

                        $name = $user->id . '_' . time() . '_' . safeUrl($name) . '.' . $extension;

                        $path = $request->file('field_' . $field->id)->storeAs(CANDIDATE_FILES, $name, 'public_uploads');


                        $file = UPLOAD_PATH . '/' . $path;
                        $customValues[$field->id] = ['value' => $file];
                    } elseif ($user->candidateFields()->where('id', $field->id)->first()) {
                        $fileName = $user->candidateFields()->where('id', $field->id)->first()->pivot->value;
                        $customValues[$field->id] = ['value' => $fileName];
                    }
                } else {
                    $customValues[$field->id] = ['value' => $requestData['field_' . $field->id]];
                }


            }
        }

        $user->candidateFields()->sync($customValues);




        return back()->with('flash_message', __('site.changes-saved'));

    }

}
