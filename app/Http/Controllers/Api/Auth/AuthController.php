<?php

namespace App\Http\Controllers\Api\Auth;

use App\CandidateField;
use App\CandidateFieldGroup;
use App\EmployerField;
use App\EmployerFieldGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateFieldGroupResource;
use App\Http\Resources\EmployerFieldGroupResource;
use App\Http\Resources\UserResource;
use App\Lib\HelperTrait;
use App\PendingUser;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    use SendsPasswordResetEmails;
    use HelperTrait;
    public function employerFields(){
        if(setting('general_enable_employer_registration')!=1){
            return abort(401);
        }
        $groups = EmployerFieldGroup::where('registration',1)->where('public',1)->orderBy('sort_order')->get();
        return  EmployerFieldGroupResource::collection($groups);
    }

    public function candidateFields(){
        if(setting('general_enable_candidate_registration')!=1){
            return abort(401);
        }
        $groups = CandidateFieldGroup::where('registration',1)->where('public',1)->orderBy('sort_order')->get();
        return  CandidateFieldGroupResource::collection($groups);
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




        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                'error'=> implode(' ',$validator->messages()->all()),
                'status'=>false
            ]);
        }

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

           return response()->json([
               'status'=>true,
               'type'=>'confirm',
               'message'=>__lang('site.email-confirmation-text')
           ]);
        }



        //First create user
        $requestData['api_token'] = Str::random(60);
        $requestData['status'] = 1;
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

        return response()->json([
            'status'=>true,
            'type'=>'registered',
            'token'=>$user->api_token,
            'user'=> new UserResource($user),
        ]);

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
        $messages = [];
        foreach(CandidateFieldGroup::where('registration',1)->where('public',1)->orderBy('sort_order')->get() as $group){

            foreach($group->candidateFields()->where('enabled',1)->get() as $field){
                if($field->type=='file'){
                    $required = '';
                    if($field->required==1){
                        $required = 'required|';
                        $messages['field_'.$field->id.'.required'] = __('validation.required',['attribute'=>$field->name]);
                    }

                    $rules['field_'.$field->id] = 'nullable|'.$required.'max:'.config('app.upload_size').'|mimes:'.config('app.upload_files');
                    $messages['field_'.$field->id.'.max'] = __('validation.max.file',['attribute'=>$field->name]);
                    $messages['field_'.$field->id.'.mimes'] = __('validation.mimes',['attribute'=>$field->name]);
                }
                elseif($field->required==1){
                    $rules['field_'.$field->id] = 'required';
                    $messages['field_'.$field->id.'.required'] = __('validation.required',['attribute'=>$field->name]);
                }

            }

        }




        $validator = Validator::make($request->all(),$rules,$messages);

        if($validator->fails()){
            return response()->json([
                'error'=> implode(' ',$validator->messages()->all()),
                'status'=>false
            ]);
        }

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

            return response()->json([
                'status'=>true,
                'type'=>'confirm',
                'message'=>__lang('site.email-confirmation-text')
            ]);
        }

        //First create user
        $requestData['api_token'] = Str::random(60);
        $requestData['status'] = 1;
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

        return response()->json([
            'status'=>true,
            'type'=>'registered',
            'token'=>$user->api_token,
            'user'=> new UserResource($user),
        ]);

    }

    public function login(Request $request){
        $data = $request->all();


        $validator = Validator::make($data,[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'error'=> implode(' ',$validator->messages()->all()),
                'status'=>false
            ]);
        }
        $email = $request->email;
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = User::where('email',$email)->first();

            //check if student has token
            $token = $user->api_token;

            if(empty($token)){
                do{
                    $token = bin2hex(random_bytes(16));
                }while(!User::where('api_token',$token));

                $user->api_token = $token;

            }


            $user->api_token = $token;
            $user->save();


            return response()->json([
                'user'=> new UserResource($user),
                'status'=>true
            ]);

        }
        else{
            return response()->json(['status'=>false,'error'=>__('auth.failed')]);
        }
    }

    public function getUser(Request $request){

        return new UserResource($request->user());

    }




    public function resetPassword(Request $request){
        $data = $request->all();

        $validator = Validator::make($data,[
            'email'=>'required|email',
        ]);

        if($validator->fails()){
            return response()->json([
                'error'=> implode(' ',$validator->messages()->all()),
                'status'=>false
            ]);
        }



        $email = $data['email'];
        $user = User::where('email',$email)->first();

        if(!$user){
            return response()->json([
                'status'=>false,
                'error'=>__('auth.no-email-assoc',['email'=>$email])
            ]);
        }

        $credentials = ['email' => $email];
        $response = __(Password::sendResetLink($credentials));

        return response()->json([
            'status'=> true,
            'msg'=>$response
        ]);;

    }


}
