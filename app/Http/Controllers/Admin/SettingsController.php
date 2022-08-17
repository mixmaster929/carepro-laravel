<?php

namespace App\Http\Controllers\Admin;

use App\PackageDuration;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{


    public function settings($group){
        $settings = Setting::where('key','LIKE',"{$group}_%")->orderBy('sort_order')->get();
 /*       $settings = Setting::where('key','LIKE',"{$group}_%")->where(function($query){
            $query->where('key','!=','general_header_scripts');
            $query->where('key','!=','general_footer_scripts');
        })->orderBy('sort_order')->get();*/

        return view('admin.settings.settings',compact('settings','group'));
    }



    public function saveSettings(Request $request){

        $requestData = $request->all();



        $files = $request->files->all();



        if(!empty($files)){
            $rules = [];

            foreach($files as $key=>$value){
                $rules[$key]='file|max:'.config('app.upload_size').'|mimes:jpeg,png,gif';
            }
            $this->validate($request,$rules);
        }




        if(!empty($files)){
            foreach($files as $key=>$value){
                if($request->hasFile($key)){

                    $setting= Setting::where('key',$key)->first();
                    if(!$setting){
                        continue;
                    }

                    @unlink($setting->value);

                    $path =  $request->file($key)->store(SETTINGS,'public_uploads');

                    $file = UPLOAD_PATH.'/'.$path;



                    $img = Image::make($file);

                    $img->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($file);


                    $setting->value = trim($file);
                    $setting->save();
                    unset($requestData[$key]);

                }
            }
        }


        foreach($requestData as $key=>$value){

            if(!is_string($value)){
           //     continue;
            }

            $setting= Setting::where('key',$key)->first();
            if($setting){
                $setting->value = trim($value);
                $setting->save();
            }

        }

        return back()->with('flash_message',__('site.changes-saved'));
    }

    public function removePicture(Setting $setting){


        @unlink($setting->value);
        $setting->value = null;
        $setting->save();
        return back()->with('flash_message',__('site.picture-removed'));
    }








    public function language(){
    //    $languages = ['en'];
        $path = '../resources/lang';
        $files = scandir($path);
        $directories = [];
        foreach($files as $key=>$value)
        {
            if(is_dir($path.'/'.$value) && $value!='.' && $value!='..'){
                $directories[] = $value;
            }
        }

        $languages = $directories;
        sort($languages);
        $controller = $this;
        return view('admin.settings.language',compact('languages','controller'));

    }

    public function saveLanguage(Request $request){
        $this->validate($request,[
            'config_language'=>'required'
        ]);

        if(Setting::where('key','config_language')->first()){
            Setting::where('key','config_language')->update(['value'=>$request->config_language]);
        }
        else{
            Setting::create([
               'key'=>'config_language',
                'value'=>$request->config_language,
                'type'=>'text'
            ]);
        }


        return back()->with('flash_message',__('site.changes-saved'));
    }

    public function languageName($code){

        $lib = config('languages.dict');
        if (isset($lib[$code])){
            return $lib[$code];
        }
        else{
            return $code;
        }

    }



    public function profile(){

        $user= Auth::user();
        return view('admin.settings.profile',compact('user'));
    }

    public function saveProfile(Request $request){

        $requestData = $request->all();
        $user = Auth::user();
        $rules = [
            'name'=>'required',
            'email'=>'required|email'
        ];

        if($requestData['email']!=$user->email){
            $rules['email'] = 'required|email|unique:users';
        }

        $this->validate($request,$rules);

        if(!empty($requestData['password'])){
            $requestData['password']= Hash::make($requestData['password']);
        }
        else{
            $requestData['password'] = $user->password;
        }

        $user->fill($requestData);
        $user->save();

        return back()->with('flash_message',__('site.changes-saved'));
    }


    public function frontend(){
        $status = setting('frontend_status');
        $options = [
          '1'=>__('site.enabled'),
          '0'=>  __('site.disabled')
        ];

        return view('admin.settings.frontend',compact('status','options'));
    }

    public function saveFrontend(Request $request){
        $this->validate($request,[
            'status'=>'required'
        ]);
        $status = $request->status;
        $setting= Setting::where('key','frontend_status')->first();
        if(!$setting){
            $setting = new Setting();
            $setting->key = 'frontend_status';
            $setting->type = 'text';
        }

        $setting->value = $status;
        $setting->save();
        flashMessage(__('site.changes-saved'));
        return back();

    }


}
