<?php

namespace App\Http\Controllers\Api\Account;

use App\Contract;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContractResource;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    use HelperTrait;
    public function index(Request $request){
        $perPage = 30;
        if($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $contracts = $request->user()->contracts()->orderBy('id','desc')->where('enabled',1)->paginate($perPage);

        return ContractResource::collection($contracts);
    }

    public function show(Request $request,Contract $contract){
        $this->authorize('view',$contract);
        $user = $request->user();

        if($contract->users()->where('id',$user->id)->first()->pivot->signed==1){

            return response()->json([
                'status'=>false,
                'message'=>__('site.already-signed')
            ]);

        }
        $content = $contract->content;

        foreach ($contract->users as $user){
            $placeholder = '[signature-'.$user->id.']';
            $replace = '<strong>['.__('site.signature').': '.strtoupper($user->name).']</strong>';
            $content = str_replace($placeholder,$replace,$content);
            $userId =$user->id;
            //replace other placeholders
            $date = '[date-'.$userId.']';
            $dateReplace = date('d/M/Y');
            $content = str_ireplace($date,$dateReplace,$content);

            //replace name
            $name = '[name-'.$userId.']';
            $nameReplace = $user->name;
            $content = str_ireplace($name,$nameReplace,$content);
        }

        return response()->json([
            'status'=>true,
            'contract' => new ContractResource($contract),
            'content'=> $content
        ]);
    }

    public function update(Request $request,Contract $contract){
        $this->validate($request,[
            'signature'=>'required|min:340'
        ],[
            'signature.min'=>__('site.invalid-signature')
        ]);
        $this->authorize('update',$contract);
        $signature = '<img  style="max-width:100px;max-height:100px" src="'.$request->signature.'" />';
        $user = $request->user();
        $html = $contract->content;
        $userId = $request->user()->id;
        $placeholder = '[signature-'.$userId.']';
        $html = str_ireplace($placeholder,$signature,$html);

        //replace other placeholders
        $date = ['date-'.$userId];
        $dateReplace = date('d/M/Y');
        $html = str_ireplace($date,$dateReplace,$html);

        //replace name
        $name = ['name-'.$userId];
        $nameReplace = $request->user()->name;
        $html = str_ireplace($name,$nameReplace,$html);

        $contract->content = $html;
        $contract->save();
        $contract->users()->updateExistingPivot($user->id,[
            'signed'=>1
        ]);
        //notify others
        foreach($contract->users()->where('id','!=',\Illuminate\Support\Facades\Auth::user()->id)->get() as $suser){
            $message = __('site.contract-signed-mail',['contract'=>$contract->name,'name'=>$user->name]);
            if($contract->users()->where('id',$suser->id)->first()->pivot->signed==0){
                $link = route('user.contract.show',['contract'=>$contract->id]).'?'.getLoginToken($suser->id);
                $mailLink = '<a href="'.$link.'">'.$link.'</a>';
                $message = $message.'<br/>'.ucfirst(strtolower(__('site.sign-here'))).': '.$mailLink;
            }
            $this->sendEmail($suser->email,__('site.contract-signed'),$message,null,null,null,false);
        }


        return response()->json([
            'status'=>true,
            'message'=>__('site.changes-saved')
        ]);

    }

    public function download(Contract $contract){
        $this->authorize('view',$contract);
        $html = $contract->content;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download(safeUrl($contract->name).'.pdf');
    }

}
