<?php

namespace App\Http\Controllers\Admin;

use App\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentMethodsController extends Controller
{
    public function index(){
        $methods = getDirectoryContents(PAYMENT_PATH);
        $paymentMethods = PaymentMethod::orderBy('name')->get();
        return view('admin.payment-methods.index',compact('paymentMethods','methods'));
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        $title = __('site.edit').' '.$paymentMethod->name;
        if($paymentMethod->translate==1){
            $title = __('site.edit').' '.__('site.'.$paymentMethod->code);
        }
        $settings = sunserialize($paymentMethod->settings);
        if (!is_array($settings)){
            $settings=[];
        }

        $form = 'payment.'.$paymentMethod->code.'.views.setup';

        return view('admin.payment-methods.edit',compact('title','paymentMethod','settings','form'));
    }

    public function update(Request $request,PaymentMethod $paymentMethod){
        $this->validate($request,[
           'method_label'=>'required'
        ]);

        $requestData = $request->all();
        unset($requestData['_token']);

        $paymentMethod->fill($requestData);
        $paymentMethod->settings = serialize($requestData);
        $paymentMethod->save();



        return redirect()->route('admin.payment-methods')->with('flash_message',__('site.changes-saved'));
    }

    public function install($method){

        //first check if this template exists yet
        $paymentMethod = PaymentMethod::where('code',$method)->first();
        if(!$paymentMethod){

            $info = paymentInfo($method);
            $paymentMethod = PaymentMethod::create([
                'name'=>__($info['name']),
                'status'=>1,
                'code'=>$method,
                'method_label'=>__($info['name']),
                'translate'=>0,
                'is_global'=>0,
            ]);

        }
        else{

            $paymentMethod->status = 1;
            $paymentMethod->save();

        }

        return back()->with('flash_message',__('site.installed'));

    }


    public function uninstall(PaymentMethod $paymentMethod){
        $paymentMethod->status = 0;
        $paymentMethod->save();
        return back()->with('flash_message',__('site.uninstalled'));
    }


}
