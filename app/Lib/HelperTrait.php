<?php
namespace App\Lib;

use App\Email;
use App\InvoiceCategory;
use App\Mail\Generic;
use App\Invoice;
use App\Sms;
use App\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait HelperTrait {

    public function successMessage($message){
        request()->session()->flash('alert-success', $message);
    }


    public function warningMessage($message){
        request()->session()->flash('alert-warning', $message);
    }


    public function errorMessage($message){
        request()->session()->flash('alert-danger', $message);
    }

    public function sendEmail($recipientEmail,$subject,$message,$from=null,$cc=null,$attachments=null,$flashError=true){

        $cc = $this->extract_emails($cc);
        try{

        if(!empty($cc)){

            //generate array from cc
            $ccArray = explode(',',$cc);
            $allCC = [];
            foreach($ccArray as $key=>$value){
                $value = trim($value);
                $validator = Validator::make(['email'=>$value],['email'=>'email']);

                if(!$validator->fails()){
                    $allCC[] = $value;
                }

            }

            Mail::to($recipientEmail)->cc($allCC)->send(New Generic($subject,$message,$from,$attachments));
        }
        else{
            Mail::to($recipientEmail)->send(New Generic($subject,$message,$from,$attachments));
        }
        return true;



    }
catch(\Exception $ex){
  //  dd($ex);
    if($flashError && !request()->expectsJson()){
        $this->warningMessage(__('site.send-failed').': '.$ex->getMessage());
    }

    return false;
}

    }

    public function sendSavedEmail(Email $email){

        $attachments = [];
        $message = $email->message;

        //check for invoices
        foreach($email->invoices as $invoice){
            $attachments[] = $this->getInvoicePdfPath($invoice);

            $message .= '<br/><h3>'.__('site.invoice').'#'.$invoice->id.'</h3><br/>'.$this->getInvoiceMailMessage($invoice);

        }

        //check for candidates
        $profileGenerator = new ProfileGenerator();
        $full = false;
        if($email->profile_type=='f'){
            $full = true;
        }
        foreach($email->candidates as $candidate){
            $profileGenerator->createPdf($candidate->user_id,true,$full,TEMP_DIR.'/');
        }

        $attachments= array_merge($attachments,$profileGenerator->getStack());

        //check for resources
        foreach($email->emailResources as $emailResource){
            //new path
            $file = TEMP_DIR.'/'.strtolower(Str::random(4)).'_'.$emailResource->file_name;
            copy($emailResource->file_path,$file);
            $attachments[] = $file;
        }

        //add attachments
        foreach($email->emailAttachments as $emailAttachment){
            //new path
            $file = TEMP_DIR.'/'.strtolower(Str::random(4)).'_'.$emailAttachment->file_name;
            copy($emailAttachment->file_path,$file);
            $attachments[] = $file;
        }





        $this->sendEmail($email->user->email,$email->subject,$message,null,$email->cc,$attachments);

        $email->sent = 1;
        $email->save();

        foreach($attachments as $attachment)
        {
            @unlink($attachment);
        }


    }

    public function getInvoicePdfPath(Invoice $invoice){
        $pdf = App::make('dompdf.wrapper')->loadView('admin.pdf.invoice', compact('invoice'))->setPaper('a4', 'portrait');
        $path = TEMP_DIR.'/'.safeUrl(setting('general_site_name')).'_invoice_'.$invoice->id.'.pdf';
        $pdf->save($path);
        return $path;
    }

    private  function extract_emails($str){
        // This regular expression extracts all emails from a string:
        $regexp = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
        preg_match_all($regexp, $str, $m);

        $emails= isset($m[0]) ? $m[0] : array();
        $newEmails = [];
        foreach($emails as $key=>$value){
            $newEmails[$value] = $value;
        }

        if(count($newEmails)>0){
            $addresses = implode(' , ',$newEmails);
            return $addresses;
        }
        else{
            return null;
        }



    }

    public function createInvoice($userId,$amount,$title,$description=null,$dueDate=null,$paymentMethodId=null,$categoryId=null,$sendMail=true)
    {
        if(empty($dueDate)){
            $dueDate = Carbon::now()->toDateString();
        }

        if(!empty($categoryId) && !InvoiceCategory::find($categoryId)){
            $categoryId = null;
        }

        $invoice = Invoice::create([
           'user_id'=>$userId,
            'amount'=> $amount,
            'title'=> $title,
            'description'=>$description,
            'due_date'=>$dueDate,
            'payment_method_id'=>$paymentMethodId,
            'invoice_category_id'=>$categoryId,
            'hash'=>Str::random(30)
        ]);

        //TODO
        //put code for sending invoice via email
        if($sendMail){
            $message = $this->getInvoiceMailMessage($invoice);
            $subject = __('site.invoice-subject');
            $attachment = $this->getInvoicePdfPath($invoice);
            $this->sendEmail($invoice->user->email,$subject,$message,null,null,$attachment);
        }

        return $invoice;

    }

    public function getInvoiceMailMessage(Invoice $invoice){

        $description = '';
        if(!empty($invoice->description)){
            $description = '<br/><strong>'.__('site.details').'</strong><br/>'.$invoice->description.'<br/>';
        }

        $message = __('site.invoice-mail',[
            'item'=>$invoice->title,
            'notes'=>$description,
            'price'=>price($invoice->amount),
            'link'=> route('cart.pay',['hash'=>$invoice->hash])
        ]);

        return $message;
    }

    public function sendSavedSMS(Sms $sms){
        //get all recipients
        $numbers =[];
        foreach($sms->smsRecipients as $recipient){
            $numbers[] = $recipient->telephone;
        }

        $smsGateway = new SmsGateway($numbers,$sms->message);
        $sms->sent = 1;
        $sms->save();
       return $smsGateway->send($sms->sms_gateway_id);
    }

    public function notifyAdmins($subject,$message,$permission=null){


        foreach(User::where('role_id',1)->get() as $user){
            if(!empty($permission) && !$user->can('access',$permission)){
                continue;
            }
            $this->sendEmail($user->email,$subject,$message);

        }

    }

    public function notifyAdminsApi($subject,$message,$permission=null){


        foreach(User::where('role_id',1)->get() as $user){
            if(!empty($permission) && !$user->can('access',$permission)){
                continue;
            }
            $this->sendEmail($user->email,$subject,$message,null,null,null,false);

        }

    }

    public function billingAddress(){
        $user = Auth::user();
        $addresses = $user->billingAddresses()->count();
        if(empty($addresses)){
            return false;
        }
        elseif(session('billing_address')){
            $addressId = session('billing_address');
            try{
                $address = $user->billingAddresses()->where('id',$addressId)->firstOrFail();
                return $address;
            }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex){

                $address = $user->billingAddresses()->first();
                return $address;
            }
        }
        else{
            //get the default address
            try{
                $address = $user->billingAddresses()->where('is_default',1)->firstOrFail();
                return $address;
            }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex){
                $address = $user->billingAddresses()->first();
                return $address;
            }

        }
    }

    public function approveInvoice(Invoice $invoice){
        $invoice->paid = 1;
        $invoice->save();

        $title = __('site.invoice-approved');
        $message = __('site.invoice-approved-msg',['invoiceId'=>$invoice->id]);
        $this->sendEmail($invoice->user->email,$title,$message);
        session()->forget('invoice');
        return $title;
    }

}

