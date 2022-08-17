<?php

namespace App\Http\Controllers\Auth;

use App\CandidateField;
use App\CandidateFieldGroup;
use App\EmployerField;
use App\EmployerFieldGroup;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\PendingUser;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use HelperTrait;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
      /*  return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);*/
    }

    public function showRegistrationForm()
    {
      //  return tview('auth.register');
    }

    public function employer(){
        if(setting('general_enable_employer_registration')!=1){
           return abort(401);
        }
        $groups = EmployerFieldGroup::where('registration',1)->where('public',1)->orderBy('sort_order')->get();
        return tview('auth.employer',compact('groups'));
    }

    public function candidate(){
        if(setting('general_enable_candidate_registration')!=1){
            return abort(401);
        }
        $groups = CandidateFieldGroup::where('registration',1)->where('public',1)->orderBy('sort_order')->get();
        return tview('auth.candidate',compact('groups'));
    }

    public function registerEmployer(Request $request){
        if(setting('general_enable_employer_registration')!=1){
            return abort(401);
        }
        $rules = [
            'name'=>'required',
            'email'=>'required|email|string|max:255|unique:users',
            'password'=>'required|min:6|confirmed',
            'telephone'=>'required'
        ];

        if(setting('captcha_employer_captcha')==1 && setting('captcha_type')=='image'){
            $rules['captcha'] = 'required|captcha';
        }


        if(setting('captcha_employer_captcha')==1 && setting('captcha_type')=='google'){

            $recaptcha_secret = setting('captcha_recaptcha_secret');
            $recaptcha_response = $request->captcha_token;

            $curl = curl_init();

            $captcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";

            curl_setopt($curl, CURLOPT_URL,$captcha_verify_url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "secret=".$recaptcha_secret."&response=".$recaptcha_response);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $captcha_output = curl_exec($curl);

            curl_close ($curl);

            $recaptcha = json_decode($captcha_output);



            // Take action based on the score returned:
            try{
                if ($recaptcha->score < 0.5) {
                    // Verified - send email
                    flashMessage(__lang('invalid-captcha'));
                    return back()->withInput();
                }
            }
            catch(\Exception $ex){
                flashMessage($ex->getMessage());
                return back()->withInput();
            }

        }


        foreach(EmployerFieldGroup::where('registration',1)->orderBy('sort_order')->get() as $group){
            foreach($group->employerFields()->where('enabled',1)->get() as $field){

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



        $password= $request->password;
        $requestData['password'] = Hash::make($password);
        $requestData['role_id'] = 2;

        //now save custom fields
        $fields = EmployerField::where('enabled',1)->get();

        //check if email verification is required
        if(setting('general_employer_verification')==1){

         do{
             $hash = Str::random(30);
         }while(PendingUser::where('hash',$hash)->first());


            $formData  = $_POST;

            $formData['role_id'] = 2;

            $pendingUser = PendingUser::create([
                'role_id'=>2,
                'data'=> serialize($formData),
                'hash'=> $hash
            ]);

            //scan for files
            foreach($fields as $field){
                if(isset($requestData['field_'.$field->id]) && $field->type=='file' && $request->hasFile('field_'.$field->id))
                {
                            //generate name for file

                            $name = $_FILES['field_'.$field->id]['name'];

                            //dd($name);


                            $extension = $request->{'field_'.$field->id}->extension();
                            //  dd($extension);

                            $name = str_ireplace('.'.$extension,'',$name);

                            $name = $pendingUser->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                            $path =  $request->file('field_'.$field->id)->storeAs(PENDING_USER_FILES,$name,'public_uploads');



                            $file = UPLOAD_PATH.'/'.$path;
                            $pendingUser->pendingUserFiles()->create([
                                'file_name'=>$_FILES['field_'.$field->id]['name'],
                                'file_path'=>$file,
                                'field_id'=>$field->id
                            ]);

                }


            }

            //send email to user
            $link = route('confirm.employer',['hash'=>$hash],true);
            $this->sendEmail($request->email,__('site.confirm-your-email'),__('site.confirm-email-mail',['link'=>$link]));
            return redirect()->route('register.confirm');
        }



        //First create user
        $requestData['api_token'] = Str::random(60);
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

        $message = __('mails.new-account',[
            'siteName'=>setting('general_site_name'),
            'email'=>$requestData['email'],
            'password'=>$password,
            'link'=> url('/login')
        ]);
        $subject = __('mails.new-account-subj',[
            'siteName'=>setting('general_site_name')
        ]);
        $this->sendEmail($requestData['email'],$subject,$message);

        //now login user
        Auth::login($user, true);

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

    public function registerCandidate(Request $request){
        if(setting('general_enable_candidate_registration')!=1){
            return abort(401);
        }

        $rules = [
            'first_name'=>'required',
            'last_name'=>'required',
            'gender'=>'required',
            'email'=>'required|email|string|max:255|unique:users',
            'date_of_birth_year'=>'required',
            'date_of_birth_month'=>'required',
            'date_of_birth_day'=>'required',
            'picture' => 'nullable|max:'.config('app.upload_size').'|mimes:jpeg,png,gif',
            'cv_path' => 'nullable|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files'),
        ];

        if(setting('captcha_candidate_captcha')==1 && setting('captcha_type')=='image'){
            $rules['captcha'] = 'required|captcha';
        }

        if(setting('captcha_candidate_captcha')==1 && setting('captcha_type')=='google' ){
            $recaptcha_secret = setting('captcha_recaptcha_secret');
            $recaptcha_response = $request->captcha_token;

            $curl = curl_init();

            $captcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";

            curl_setopt($curl, CURLOPT_URL,$captcha_verify_url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "secret=".$recaptcha_secret."&response=".$recaptcha_response);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $captcha_output = curl_exec($curl);

            curl_close ($curl);

            $recaptcha = json_decode($captcha_output);



            // Take action based on the score returned:
            try{
                if ($recaptcha->score < 0.5) {
                    // Verified - send email
                    flashMessage(__lang('invalid-captcha'));
                    return back()->withInput();
                }
            }
            catch(\Exception $ex){
                flashMessage($ex->getMessage());
                return back()->withInput();
            }

        }


        foreach(CandidateFieldGroup::where('registration',1)->where('public',1)->orderBy('sort_order')->get() as $group){

            foreach($group->candidateFields()->where('enabled',1)->get() as $field){
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
        $password= $request->password;

        $requestData['name']= $request->first_name.' '.$request->last_name;
        $requestData['display_name'] = $request->first_name;
        $requestData['password'] = Hash::make($password);
        $requestData['role_id'] = 3;

        $fields = CandidateField::where('enabled',1)->get();




        //check if email verification is required
        if(setting('general_candidate_verification')==1){

            do{
                $hash = Str::random(30);
            }while(PendingUser::where('hash',$hash)->first());


            $formData = $_POST;

            $formData['name'] = $request->first_name.' '.$request->last_name;
            $formData['display_name'] = $request->first_name;
            $formData['role_id'] = 3;

            if($request->hasFile('picture')) {

                $path =  $request->file('picture')->store(PENDING_USER_FILES,'public_uploads');

                $file = UPLOAD_PATH.'/'.$path;
                $img = Image::make($file);

                $img->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($file);

                $formData['picture'] = $file;
            }
            else{

                $formData['picture'] =null;
            }

            if($request->hasFile('cv_path')) {

                //$path =  $request->file('cv_path')->store(CANDIDATES,'public_uploads');

                $name = $_FILES['cv_path']['name'];

                $extension = $request->cv_path->extension();
                //  dd($extension);

                $name = str_ireplace('.'.$extension,'',$name);

                $name = uniqid().'_'.time().'_'.safeUrl($name).'.'.$extension;

                $path =  $request->file('cv_path')->storeAs(PENDING_USER_FILES,$name,'public_uploads');



                $file = UPLOAD_PATH.'/'.$path;

                $formData['cv_path'] = $file;
            }
            else{

                $formData['cv_path'] =null;
            }


            $pendingUser = PendingUser::create([
                'role_id'=>3,
                'data'=> serialize($formData),
                'hash'=> $hash
            ]);

            //scan for files
            foreach($fields as $field){
                if(isset($requestData['field_'.$field->id]) && $field->type=='file' && $request->hasFile('field_'.$field->id))
                {
                    //generate name for file

                    $name = $_FILES['field_'.$field->id]['name'];

                    //dd($name);


                    $extension = $request->{'field_'.$field->id}->extension();
                    //  dd($extension);

                    $name = str_ireplace('.'.$extension,'',$name);

                    $name = $pendingUser->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                    $path =  $request->file('field_'.$field->id)->storeAs(PENDING_USER_FILES,$name,'public_uploads');



                    $file = UPLOAD_PATH.'/'.$path;
                    $pendingUser->pendingUserFiles()->create([
                        'file_name'=>$_FILES['field_'.$field->id]['name'],
                        'file_path'=>$file,
                        'field_id'=>$field->id
                    ]);

                }


            }

            //send email to user
            $link = route('confirm.candidate',['hash'=>$hash]);
            $this->sendEmail($request->email,__('site.confirm-your-email'),__('site.confirm-email-mail',['link'=>$link]));
            return redirect()->route('register.confirm');
        }

        //First create user
        $requestData['api_token'] = Str::random(60);
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
        else{

            $requestData['picture'] =null;
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

        $message = __('mails.new-account',[
            'siteName'=>setting('general_site_name'),
            'email'=>$requestData['email'],
            'password'=>$password,
            'link'=> url('/login')
        ]);
        $subject = __('mails.new-account-subj',[
            'siteName'=>setting('general_site_name')
        ]);
        $this->sendEmail($requestData['email'],$subject,$message);

        //now login user
        Auth::login($user, true);

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


    public function confirmEmployer($hash){
        //get pending user
        $pendingUser = PendingUser::where('hash',$hash)->first();
        if(!$pendingUser){
            abort(404);
        }

        $requestData = unserialize($pendingUser->data);
        $password = $requestData['password'];
        $requestData['password'] = Hash::make($password);

        //First create user
        $requestData['api_token'] = Str::random(60);
        $user= User::create($requestData);

        $user->employer()->create($requestData);

        $fields = EmployerField::get();

        $customValues = [];
        //attach custom values
        foreach($fields as $field){

                if($field->type=='file'){
                    $pendingFile = $pendingUser->pendingUserFiles()->where('field_id',$field->id)->first();

                    if($pendingFile){

                        //generate name for file
                        $name = $pendingFile->file_name;

                        $info = new \SplFileInfo($name);

                        $extension = $info->getExtension();

                        $name = str_ireplace('.'.$extension,'',$name);

                        $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                        $file = UPLOAD_PATH.'/'.EMPLOYER_FILES.'/'.$name;

                        rename($pendingFile->file_path,$file);

                        $customValues[$field->id] = ['value'=>$file];
                    }
                }
                elseif(isset($requestData['field_'.$field->id])){
                    $customValues[$field->id] = ['value'=>$requestData['field_'.$field->id]];
                }


        }

        $user->employerFields()->sync($customValues);
        $pendingUser->delete();

        $message = __('mails.new-account',[
            'siteName'=>setting('general_site_name'),
            'email'=>$requestData['email'],
            'password'=>$password,
            'link'=> url('/login')
        ]);
        $subject = __('mails.new-account-subj',[
            'siteName'=>setting('general_site_name')
        ]);
        $this->sendEmail($requestData['email'],$subject,$message);

        //now login user
        Auth::login($user, true);

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

    public function confirmCandidate($hash){
        //get pending user
        $pendingUser = PendingUser::where('hash',$hash)->first();
        if(!$pendingUser){
            abort(404);
        }

        $requestData = unserialize($pendingUser->data);
        $password = $requestData['password'];
        $requestData['password'] = Hash::make($password);

        //check for profile picture and move to new directory
        if(!empty($requestData['picture']) && file_exists($requestData['picture'])){

            $file = basename($requestData['picture']);

            $newPath = UPLOAD_PATH.'/'.CANDIDATES.'/'.$file;
            rename($requestData['picture'],$newPath);
            $requestData['picture'] = $newPath;

        }

        if(!empty($requestData['cv_path']) && file_exists($requestData['cv_path'])){

            $file = basename($requestData['cv_path']);

            $newPath = UPLOAD_PATH.'/'.CANDIDATE_FILES.'/'.$file;
            rename($requestData['cv_path'],$newPath);
            $requestData['cv_path'] = $newPath;

        }


        //First create user
        $requestData['api_token'] = Str::random(60);
        $user= User::create($requestData);

        //Calculate date of birth
        $dateOfBirth = $requestData['date_of_birth_year'].'-'.$requestData['date_of_birth_month'].'-'.$requestData['date_of_birth_day'];
        $requestData['date_of_birth'] = $dateOfBirth;

        $user->candidate()->create($requestData);

        //save categories
        if(isset($requestData['categories'])){
            $user->candidate->categories()->attach($requestData['categories']);
        }


        $fields = CandidateField::get();

        $customValues = [];
        //attach custom values
        foreach($fields as $field){

            if($field->type=='file'){
                $pendingFile = $pendingUser->pendingUserFiles()->where('field_id',$field->id)->first();

                if($pendingFile){

                    //generate name for file
                    $name = $pendingFile->file_name;

                    $info = new \SplFileInfo($name);

                    $extension = $info->getExtension();

                    $name = str_ireplace('.'.$extension,'',$name);

                    $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                    $file = UPLOAD_PATH.'/'.CANDIDATE_FILES.'/'.$name;

                    rename($pendingFile->file_path,$file);

                    $customValues[$field->id] = ['value'=>$file];
                }
            }
            elseif(isset($requestData['field_'.$field->id])){
                $customValues[$field->id] = ['value'=>$requestData['field_'.$field->id]];
            }


        }

        $user->candidateFields()->sync($customValues);
        $pendingUser->delete();

        $message = __('mails.new-account',[
            'siteName'=>setting('general_site_name'),
            'email'=>$requestData['email'],
            'password'=>$password,
            'link'=> url('/login')
        ]);
        $subject = __('mails.new-account-subj',[
            'siteName'=>setting('general_site_name')
        ]);
        $this->sendEmail($requestData['email'],$subject,$message);

        //now login user
        Auth::login($user, true);

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

    public function confirm(){
        return tview('auth.confirm');
    }


}
