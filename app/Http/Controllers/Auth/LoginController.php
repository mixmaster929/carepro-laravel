<?php

namespace App\Http\Controllers\Auth;

use App\CandidateField;
use App\CandidateFieldGroup;
use App\EmployerField;
use App\EmployerFieldGroup;
use App\Http\Controllers\Controller;
use App\User;
use Hybridauth\Hybridauth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';



    protected function redirectTo()
    {
        session()->remove('homeUrl');

        $user = Auth::user();
        if($user->role_id==1){
            return route('admin.dashboard');
        }
        elseif($user->role_id==2){

            if(session()->exists('employer_destination')){
                $url = session()->get('employer_destination');
                session()->remove('employer_destination');
                return $url;
            }
            else{
                return route('employer.dashboard');
            }

        }
        elseif($user->role_id==3){

            if(session()->exists('candidate_destination')){
                $url = session()->get('candidate_destination');
                session()->remove('candidate_destination');
                return $url;
            }
            else{
                return route('candidate.dashboard');
            }


        }

    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        //attempt to auto login if token is present
        $url = URL::previous();
        $url_components = parse_url($url);

        if(isset($url_components['query'])){

            parse_str($url_components['query'], $params);
            if(isset($params['login-token']) && User::where('login_token',$params['login-token'])->where('login_token_expires','>=',Carbon::now()->toDateString())->first()){
                $user = User::where('login_token',$params['login-token'])->first();
                Auth::login($user);
                return redirect($url);
            }

        }



        $enableRegistration = true;
        if(empty(setting('general_enable_candidate_registration')) && empty(setting('general_enable_employer_registration'))){
            $enableRegistration=false;
        }

        return tview('auth.login',compact('enableRegistration'));
    }

    //login via selected network. Then ask the user to select their role.
    public function social($network,Request $request){

     /*   $userProfile = new \stdClass();
        $userProfile->firstName = 'Ayokunle';
        return tview('auth.social',compact('userProfile'));*/

        //create config
        $config = array('callback'=> route('social.login',['network'=>$network]));

        if (setting('social_enable_facebook')==1) {
            $config['providers']['Facebook']=  array (
                "enabled" => true,
                "keys"    => array ( "id" => trim(setting('social_facebook_app_id')), "secret" => trim(setting('social_facebook_app_secret')) ),
                "scope"   => "email",
                "trustForwarded" => false
            );
        }

        if (setting('social_enable_google')==1) {
            $config['providers']['Google']=  array (
                "enabled" => true,
                "keys"    => array ( "id" => trim(setting('social_google_app_id')), "secret" => trim(setting('social_google_app_secret')) ),

            );
        }

        $config['debug_mode']=true;
        $config['debug_file']='hybridlog.txt';

        try{

            // create an instance for Hybridauth with the configuration file path as parameter
            $hybridauth = new Hybridauth( $config );

            // try to authenticate the user with twitter,
            // user will be redirected to Twitter for authentication,
            // if he already did, then Hybridauth will ignore this step and return an instance of the adapter
            $authSession = $hybridauth->authenticate($network);


            // get the user profile
            $userProfile = $authSession->getUserProfile();

            //check if the user exists
            $email = $userProfile->email;
            $user = User::where('email',$email)->first();
            if($user){
                //now login user
                Auth::login($user);

                //determine redirection
                if($user->role_id==2){
                    if(session()->exists('employer_destination')){
                        $url = session()->get('employer_destination');
                        session()->remove('employer_destination');
                        return redirect($url);
                    }
                    else{
                        return redirect()->route('home');
                    }
                }
                else{
                    if(session()->exists('candidate_destination')){
                        $url = session()->get('candidate_destination');
                        session()->remove('candidate_destination');
                        return redirect($url);
                    }
                    else{
                        return redirect()->route('home');
                    }
                }

            }
            elseif(empty(setting('general_enable_candidate_registration')) && empty(setting('general_enable_employer_registration'))){
                return redirect()->route('login')->with('flash_message',__('site.registration-disabled'));
            }

            $userClass = new \stdClass();
            $userClass->firstName = $userProfile->firstName;
            $userClass->lastName = $userProfile->lastName;
            $userClass->email = $userProfile->email;
            $userClass->phone = $userProfile->phone;
            $userClass->gender = $userProfile->gender;
            $userClass->photoURL= $userProfile->photoURL;
            $userClass->birthDay = $userProfile->birthDay;
            $userClass->birthMonth = $userProfile->birthMonth;
            $userClass->birthYear = $userProfile->birthYear;

            //store user in session
            session()->put('social_user',serialize($userClass));


        }catch(\Exception $ex){
            return back()->with('flash_message',$ex->getMessage());
        }

        //check if employer or candidate registration is disabled
        if(setting('general_enable_employer_registration')==1 && setting('general_enable_candidate_registration')!=1){
            //redirect to employer
            return redirect()->route('social.employer');
        }
        elseif(setting('general_enable_candidate_registration')==1 && setting('general_enable_employer_registration')!=1){
            return redirect()->route('social.candidate');
        }


        return tview('auth.social',compact('userProfile'));
    }



    public function completeEmployer(){
        //now get all relevant fields
        if(setting('general_enable_employer_registration')!=1){
            return abort(401);
        }
        $groups = EmployerFieldGroup::where('registration',1)->orderBy('sort_order')->get();
        $user = session('social_user');
        if(!$user){
            return redirect()->route('login')->with('flash_message',__('site.invalid-login'));
        }

        $user  = unserialize($user);

        $name= $user->firstName.' '.$user->lastName;

        return tview('auth.social.employer',compact('groups','name','user'));
    }

    public function saveEmployer(Request $request){
        if(setting('general_enable_employer_registration')!=1){
            return abort(401);
        }

        $socialUser = session('social_user');
        if(!$socialUser){
            return redirect()->route('login')->with('flash_message',__('site.invalid-login'));
        }

        $socialUser  = unserialize($socialUser);

        $name= $socialUser->firstName.' '.$socialUser->lastName;

        $rules = [
            'telephone'=>'required'
        ];

        foreach(EmployerField::get() as $field){

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
        $this->validate($request,$rules);
        $requestData = $request->all();

        $requestData['name']= $name;
        $requestData['email'] = $socialUser->email;


        $password= Str::random(10);
        $requestData['password'] = Hash::make($password);
        $requestData['role_id'] = 2;

        //now save custom fields
        $fields = EmployerField::get();

        //First create user
        $user= User::create($requestData);



        $user->employer()->create($requestData);



        $customValues = [];
        //attach custom values
        foreach($fields as $field){
            if(isset($requestData['field_'.$field->id]))
            {

                if($field->type=='file'){
                    if($request->hasFile('field_'.$field->id)){
                        //generate name for file

                        $name = $_FILES['field_'.$field->id]['name'];

                        //dd($name);


                        $extension = $request->{'field_'.$field->id}->extension();
                        //  dd($extension);

                        $name = str_ireplace('.'.$extension,'',$name);

                        $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                        $path =  $request->file('field_'.$field->id)->storeAs(EMPLOYER_FILES,$name,'public_uploads');



                        $file = UPLOAD_PATH.'/'.$path;
                        $customValues[$field->id] = ['value'=>$file];
                    }
                }
                else{
                    $customValues[$field->id] = ['value'=>$requestData['field_'.$field->id]];
                }

            }
        }

        $user->employerFields()->sync($customValues);

        //now login user
        Auth::login($user, true);
        session()->remove('social_user');

        //redirect to relevant page
        if(session()->exists('employer_destination')){
            $url = session()->get('employer_destination');
            session()->remove('employer_destination');
            return redirect($url);
        }
        else{
            return redirect()->route('home');
        }

    }

    public function completeCandidate(){
        if(setting('general_enable_candidate_registration')!=1){
            return abort(401);
        }
        $groups = CandidateFieldGroup::where('registration',1)->orderBy('sort_order')->get();
        $user = session('social_user');
        if(!$user){
            return redirect()->route('login')->with('flash_message',__('site.invalid-login'));
        }

        $user  = unserialize($user);
        $gender = strtolower(substr($user->gender,0,1));

        return tview('auth.social.candidate',compact('groups','user','gender'));
    }

    public function saveCandidate(Request $request){
        if(setting('general_enable_candidate_registration')!=1){
            return abort(401);
        }
        $socialUser = session('social_user');
        if(!$socialUser){
            return redirect()->route('login')->with('flash_message',__('site.invalid-login'));
        }

        $socialUser  = unserialize($socialUser);

        $name= $socialUser->firstName.' '.$socialUser->lastName;

        $rules = [
            'gender'=>'required',
            'date_of_birth_year'=>'required',
            'date_of_birth_month'=>'required',
            'date_of_birth_day'=>'required',
            'picture' => 'nullable|max:'.config('app.upload_size').'|mimes:jpeg,png,gif',
            'cv_path' => 'nullable|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files'),
        ];

        foreach(CandidateField::get() as $field){

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


        $this->validate($request,$rules);
        $requestData = $request->all();
        $password= Str::random(10);

        $requestData['name']= $name;
        $requestData['display_name'] = $socialUser->firstName;
        $requestData['email'] = $socialUser->email;
        $requestData['password'] = Hash::make($password);
        $requestData['role_id'] = 3;

        $fields = CandidateField::get();

        //First create user
        $user= User::create($requestData);

        //Calculate date of birth
        $dateOfBirth = $request->date_of_birth_year.'-'.$request->date_of_birth_month.'-'.$request->date_of_birth_day;
        $requestData['date_of_birth'] = $dateOfBirth;

        //checkfor picture
        if($request->hasFile('picture')) {

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
        elseif(!empty($socialUser->photoURL)){
            //download the image
            try{
                $photoUrl = $socialUser->photoURL;
                $photoUrl = str_ireplace('=150','=500',$photoUrl);
                $remoteName = basename($photoUrl);
                $filename = time().'-'.$remoteName;
                $tempImage = tempnam(sys_get_temp_dir(), $filename);
                copy($photoUrl, $tempImage);

                $file = UPLOAD_PATH.'/'.CANDIDATES.'/social-'.uniqid().'.jpg';
                $img = Image::make($tempImage);

                $img->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($file);

                $requestData['picture'] = $file;

                @unlink($tempImage);

            }
            catch(\Exception $ex){
                //dd($ex->getMessage());
            }


            //$requestData['picture'] =$socialUser->photoURL;
        }

        if($request->hasFile('cv_path')) {

            //$path =  $request->file('cv_path')->store(CANDIDATES,'public_uploads');

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

            $requestData['cv_path'] =null;
        }



        $user->candidate()->create($requestData);

        //save categories
        $user->candidate->categories()->attach($request->categories);

        //now save custom fields


        $customValues = [];
        //attach custom values
        foreach($fields as $field){
            if(isset($requestData['field_'.$field->id]))
            {

                if($field->type=='file'){
                    if($request->hasFile('field_'.$field->id)){
                        //generate name for file

                        $name = $_FILES['field_'.$field->id]['name'];

                        $extension = $request->{'field_'.$field->id}->extension();

                        $name = str_ireplace('.'.$extension,'',$name);

                        $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                        $path =  $request->file('field_'.$field->id)->storeAs(CANDIDATE_FILES,$name,'public_uploads');

                        $file = UPLOAD_PATH.'/'.$path;
                        $customValues[$field->id] = ['value'=>$file];
                    }
                }
                else{
                    $customValues[$field->id] = ['value'=>$requestData['field_'.$field->id]];
                }
            }


        }

        $user->candidateFields()->sync($customValues);

        //now login user
        Auth::login($user, true);
        session()->remove('social_user');

        //redirect to relevant page
        if(session()->exists('candidate_destination')){
            $url = session()->get('candidate_destination');
            session()->remove('candidate_destination');
            return redirect($url);
        }
        else{
            return redirect()->route('home');
        }


    }

}
