<?php

namespace App\Http\Controllers\Auth;

use App\EmployerField;
use App\Http\Controllers\Controller;
use App\PendingUser;
use App\User;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

 //   use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
   /*     $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');*/
    }

    public function verify($hash){

        $pendingUser = PendingUser::where('hash',$hash)->first();

        if(!$pendingUser){
            abort(404);
        }

        $data = unserialize($pendingUser->data);

        if($pendingUser->role_id==2){
            return $this->createEmployer($data);
        }
        elseif($pendingUser->role_id==3){
            return $this->createCandidate($data);
        }



    }

    private function createEmployer($data){


        $password= $data['password'];
        $data['password'] = Hash::make($password);
        $data['role_id'] = 2;

        //First create user
        $user= User::create($data);



        $user->employer()->create($data);

        //now save custom fields
        $fields = EmployerField::get();

        $customValues = [];
        //attach custom values
        foreach($fields as $field){
            if(isset($data['field_'.$field->id]))
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
                    $customValues[$field->id] = ['value'=>$data['field_'.$field->id]];
                }



            }


        }

        $user->employerFields()->sync($customValues);

        $message = __('mails.new-account',[
            'siteName'=>setting('general_site_name'),
            'email'=>$data['email'],
            'password'=>$password,
            'link'=> url('/login')
        ]);
        $subject = __('mails.new-account-subj',[
            'siteName'=>setting('general_site_name')
        ]);
        $this->sendEmail($data['email'],$subject,$message);

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

    private function createCandidate($data){

    }


}
