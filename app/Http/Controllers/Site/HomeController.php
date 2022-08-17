<?php

namespace App\Http\Controllers\Site;

use App\Article;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use HelperTrait;
    public function index(){

        //check if installation file exists and redirect to install if not
        if(!file_exists('../storage/installed')){
            return redirect('/install');
        }

        return tview('site.home.index');
    }

    public function article($slug){

        $article = Article::where('slug',$slug)->where('status',1)->first();
        if(!$article){
          return  abort(404);
        }


        return tview('site.home.article',compact('article'));
    }

    public function contact(){
        return tview('site.home.contact');
    }

    public function sendMail(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'message'=>'required',
            'captcha' => 'required|captcha'
        ]);

        if(!empty(setting('general_admin_email')))
        {
            $this->sendEmail(setting('general_admin_email'),__('site.contact-form-message'),$request->message,['address'=>$request->email,'name'=>$request->name]);
        }

        return back()->with('flash_message',__('site.message-sent'));

    }

}
