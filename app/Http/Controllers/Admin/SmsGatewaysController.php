<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SmsGateway;
use Illuminate\Http\Request;

class SmsGatewaysController extends Controller
{

    public function smsGateways()
    {

        $activeGateway = SmsGateway::where('active',1)->first();

        //get all gateways
        $smsGateways = SmsGateway::orderBy('gateway_name')->paginate(30);
        $gateways = getDirectoryContents(MESSAGING_PATH);

        return view('admin.sms-gateways.sms_gateway',compact('activeGateway','smsGateways','gateways'));
    }




    public function saveSmsSetting(Request $request){

        $this->validate($request,[
            'sms_max_pages'=>'required'
        ]);

        return back()->with('flash_message',__('site.changes-saved'));

    }

    public function smsFieldsOld(SmsGateway $smsGateway){


        return view('admin.sms-gateways.sms_fields',compact('smsGateway'));
    }

    public function smsFields(SmsGateway $smsGateway){
        $settings = sunserialize($smsGateway->settings);
        if (!is_array($settings)){
            $settings=[];
        }
        $form = 'messaging.'.$smsGateway->code.'.setup';
        return view('admin.sms-gateways.sms_fields',compact('settings','form', 'smsGateway'));
    }

    public function saveField(Request $request,SmsGateway $smsGateway){
/*
        $requestData = $request->all();

        foreach($smsGateway->smsGatewayFields as $field){
            $field->value = $requestData[$field->key];
            $field->save();
        }
*/

        $data = $request->all();
        unset($data['_token']);
        $smsGateway->fill($data);
        $smsGateway->settings = serialize($data);
        $smsGateway->save();

        return back()->with('flash_message',__('site.changes-saved'));

    }

    public function setSmsStatus(SmsGateway $smsGateway,$status){

        $smsGateway->active= $status;
        $smsGateway->save();
        return back()->with('flash_message',__('site.changes-saved'));
    }

    public function install($gateway){
        //first check if this template exists yet
        $smsGateway = SmsGateway::where('code',$gateway)->first();
        if(!$smsGateway){
            $info = smsInfo($gateway);
            $smsGateway = SmsGateway::create([
                'gateway_name'=>$info['name'],
                'active'=>1,
                'code'=>$gateway,
                'settings'=>serialize([])
            ]);

        }
        else{

            $smsGateway->active = 1;
            $smsGateway->save();
        }

        return back()->with('flash_message',__('site.installed'));

    }

}
