<?php

namespace App\Http\Controllers\Account;

use App\Contract;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
        use HelperTrait;
        public function index(){
            $perPage = 30;
            $contracts = Auth::user()->contracts()->orderBy('id','desc')->where('enabled',1)
                ->paginate(30);

            return view('account.contract.index',[
                'contracts'=>$contracts,
                'user'=>Auth::user(),
                'perPage'=>$perPage
            ]);
        }

        public function show(Contract $contract){
            $this->authorize('view',$contract);
            $user = Auth::user();

            if($contract->users()->where('id',$user->id)->first()->pivot->signed==1){
                flashMessage(__('site.already-signed'));
                return redirect()->route('user.contract.index');
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

                //replace address
                $address = '[address-'.$userId.']';
                if(isEmployer()){
                    $addressReplace = $user->employerFields()->where('name', 'Adres')->first()->pivot->value;
                }
                if(isCandidate()){
                    $addressReplace = $user->candidateFields()->where('name','Adres')->first()->pivot->value;
                }
                $content = str_ireplace($address,$addressReplace,$content);

                //replace postalcode
                $postalcode = '[postalcode-'.$userId.']';
                if(isEmployer()){
                    $postalcodeReplace = $user->employerFields()->where('name', 'Postcode')->first()->pivot->value;
                }
                if(isCandidate()){
                    $postalcodeReplace = $user->candidateFields()->where('name','Postcode')->first()->pivot->value;
                }
                $content = str_ireplace($postalcode,$postalcodeReplace,$content);

                //replace location
                $location = '[location-'.$userId.']';
                if(isEmployer()){
                    $locationReplace = $user->employerFields()->where('name', 'Plaats')->first()->pivot->value;
                }
                if(isCandidate()){
                    $locationReplace = $user->candidateFields()->where('name','Standplaats')->first()->pivot->value;
                }
                $content = str_ireplace($location,$locationReplace,$content);

                //replace tax number
                $tax_number = '[tax_number-'.$userId.']';
                if(isEmployer()){
                    $tax_numberReplace = $user->employerFields()->where('name', 'KvK nummer')->first()->pivot->value;
                }
                if(isCandidate()){
                    $tax_numberReplace = $user->candidateFields()->where('name','KvK Handelsregister')->first()->pivot->value;
                }
                $content = str_ireplace($tax_number,$tax_numberReplace,$content);

                //replace chamber of commerce number
                $commerce_number = '[commerce_number-'.$userId.']';
                if(isEmployer()){
                    $commerce_numberReplace = $user->employerFields()->where('name', 'BTW nummer')->first()->pivot->value;
                }
                if(isCandidate()){
                    $commerce_numberReplace = $user->candidateFields()->where('name','BTW nummer')->first()->pivot->value;
                }
                $content = str_ireplace($commerce_number,$commerce_numberReplace,$content);
            }

            return view('account.contract.show',compact('contract','content'));
        }

        public function update(Request $request,Contract $contract){
            $this->validate($request,[
             'signature'=>'required|min:340'
                ],[
                    'signature.min'=>__('site.invalid-signature')
            ]);
            $this->authorize('update',$contract);
            $signature = '<img  style="max-width:100px;max-height:100px" src="'.$request->signature.'" />';
            $user = Auth::user();
            $html = $contract->content;
            $userId = Auth::user()->id;
            $placeholder = '[signature-'.$userId.']';
            $html = str_ireplace($placeholder,$signature,$html);

            //replace other placeholders
            $date = ['date-'.$userId];
            $dateReplace = date('d/M/Y');
            $html = str_ireplace($date,$dateReplace,$html);

            //replace name
            $name = ['name-'.$userId];
            $nameReplace = Auth::user()->name;
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
                $this->sendEmail($suser->email,__('site.contract-signed'),$message);
            }


           flashMessage(__('site.changes-saved'));
           return redirect()->route('user.contract.index');

        }

        public function download(Contract $contract){
            $this->authorize('view',$contract);
            $html = $contract->content;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($html)->setPaper('a4', 'portrait');
            return $pdf->download(safeUrl($contract->name).'.pdf');
        }

}
