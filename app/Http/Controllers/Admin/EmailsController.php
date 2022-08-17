<?php

namespace App\Http\Controllers\Admin;

use App\EmailAttachment;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Email;
use App\Lib\HelperTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmailsController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_emails');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $emails = Email::latest()->where(function($query) use($keyword){
                $query->whereHas('user',function($query) use ($keyword) {
                    $query->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                     });
                $query->orWhereRaw("match(subject,message) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
             });
        } else {
            $emails = Email::latest();
        }

        $params = $request->all();
        //filter by min_date
        if(isset($params['min_date']) && $params['min_date'] != '' )
        {
            $emails = $emails->where('send_date','>=',$params['min_date']);
        }

        //filter by max_date
        if(isset($params['max_date']) && $params['max_date'] != '' )
        {
            $emails = $emails->where('send_date','<=',Carbon::parse($params['max_date'].' 23:59:59')->toDateTimeString());
        }

        if(isset($params['sent']) &&  $params['sent'] != '' ){
            $emails = $emails->where('sent',$params['sent']);
        }


        if(isset($params['user']) && $params['user'] != '' )
        {

            $emails = $emails->where('user_id',$params['user']);

        }

        $emails = $emails->paginate($perPage);

        unset($params['search'],$params['page'],$params['field_id'],$params['sent']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;
        }

        return view('admin.emails.index', compact('emails','perPage','filterParams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_email');
        $msgId = Str::random(10);
        return view('admin.emails.create',compact('msgId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('access','create_email');

        $rules = [
            'subject'=>'required',
            'user_id'=>'required'
        ];

        $requestData = $request->all();

        if($request->attach_invoice==1){
            $rules['invoice_title'] = 'required';
            $rules['invoice_amount'] = 'required';
        }

        $this->validate($request,$rules);



        $requestData['message'] = saveInlineImages($requestData['message']);

        $requestData['sender_id'] = Auth::user()->id;
        $requestData['recipient_email'] = User::find($request->user_id)->email;

        if(empty($request->send_date)){
            $requestData['send_date'] = Carbon::now()->toDateString();
        }
        
        $email= Email::create($requestData);

        //store candidates
        $email->candidates()->attach($request->candidates);

        //store resources
        $email->emailResources()->attach($request->resources);

        //create invoice
        if($request->attach_invoice==1){
            $invoice = $this->createInvoice($request->user_id,$request->invoice_amount,$request->invoice_title,$request->invoice_description,null,null,$request->invoice_category_id,false);
            $email->invoices()->attach($invoice->id);
        }

        //attach files
        //get email id
        $messageId = $requestData['msg_id'];

        //check for any attachments
        $path = '../storage/tmp/'.$messageId;

        //scan directory for files
        if(is_dir($path)){


            //$files = scandir($path);
            $files = array_diff(scandir($path), array('.', '..'));

            if(count($files) > 0){
                //check for directory
                $destDir = UPLOAD_PATH.'/'.EMAIL_FILES.'/'.$email->id;

                if(!is_dir($destDir)){
                    rmkdir($destDir);
                }

                foreach($files as $value){
                    $newName = $destDir.'/'.$value;
                    $oldName = $path.'/'.$value;
                    rename($oldName,$newName);
                    //attach record
                    $email->emailAttachments()->create([
                        'file_name'=>$value,
                        'file_path'=>$newName
                    ]);
                }
            }
            @rmdir($path);
        }

        if($requestData['sent']==1){
            $this->sendSavedEmail($email);
        }


        return redirect('admin/emails')->with('flash_message', __('site.changes-saved'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $this->authorize('access','view_email');
        $email = Email::findOrFail($id);

        return view('admin.emails.show', compact('email'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $this->authorize('access','edit_email');
        $msgId = Str::random(10);
        $email = Email::findOrFail($id);

        return view('admin.emails.edit', compact('email','msgId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->authorize('access','edit_email');

        $rules = [
            'subject'=>'required',
            'user_id'=>'required'
        ];

        $requestData = $request->all();


        if($request->attach_invoice==1){
            $rules['invoice_title'] = 'required';
            $rules['invoice_amount'] = 'required';
        }

        $this->validate($request,$rules);

        if(empty($request->send_date)){
            $requestData['send_date'] = Carbon::now()->toDateString();
        }


        $requestData['message'] = saveInlineImages($requestData['message']);
        
        $email = Email::findOrFail($id);
        $email->update($requestData);

        //store candidates
        $email->candidates()->sync($request->candidates);

        //store resources
        $email->emailResources()->sync($request->resources);

        //create invoice
        if($request->attach_invoice==1){
            $invoice = $this->createInvoice($request->user_id,$request->invoice_amount,$request->invoice_title,$request->invoice_description,null,null,$request->invoice_category_id,false);
            $email->invoices()->attach($invoice->id);
        }

        //attach files
        //get email id
        $messageId = $requestData['msg_id'];

        //check for any attachments
        $path = '../storage/tmp/'.$messageId;

        //scan directory for files
        if(is_dir($path)){


            //$files = scandir($path);
            $files = array_diff(scandir($path), array('.', '..'));

            if(count($files) > 0){
                //check for directory
                $destDir = UPLOAD_PATH.'/'.EMAIL_FILES.'/'.$email->id;

                if(!is_dir($destDir)){
                    rmkdir($destDir);
                }

                foreach($files as $value){
                    $newName = $destDir.'/'.$value;
                    $oldName = $path.'/'.$value;
                    rename($oldName,$newName);
                    //attach record
                    $email->emailAttachments()->create([
                        'file_name'=>$value,
                        'file_path'=>$newName
                    ]);
                }
            }
            @rmdir($path);
        }

        return redirect(url('admin/emails').'?sent='.$email->sent)->with('flash_message', __('site.changes-saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $sent = Email::find($id)->sent;
        $this->authorize('access','delete_email');
        Email::destroy($id);

        return redirect(url('admin/emails'))->with('flash_message', __('site.record-deleted'));
    }




    public function downloadAttachment(EmailAttachment $emailAttachment){
        $this->authorize('access','view_email');
        $path = $emailAttachment->file_path;

        header('Content-type: '.getFileMimeType($path));

// It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

// The PDF source is in original.pdf
        readfile($path);
        exit();
    }

    public function downloadAttachments(Email $email){
        $this->authorize('access','view_email');
        $zipname = __('site.attachments').'.zip';
        $zip = new \ZipArchive;
        $zip->open($zipname, \ZipArchive::CREATE);


        $deleteFiles = [];

        foreach ($email->emailAttachments as $row) {
            $path =  $row->file_path;

            if (file_exists($path)) {
                $newFile = basename($path);
                copy($path,$newFile);
                $zip->addFile($newFile);

                $deleteFiles[] = $newFile;
            }



        }
        $zip->close();

        foreach($deleteFiles as $value){
            unlink($value);
        }

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
        unlink($zipname);
        exit();
    }

    public function deleteAttachment(EmailAttachment $emailAttachment){
        $this->authorize('access','edit_email');
        @unlink($emailAttachment->file_path);
        $emailAttachment->delete();
        return back()->with('flash_message', __('site.record-deleted'));

    }

    public function sendNow(Email $email){
        $this->sendSavedEmail($email);
        return back()->with('flash_message',__('site.message-sent'));
    }


    public function deleteMultiple(Request $request){
        $this->authorize('access','delete_email');
        $data = $request->all();
        $count = 0;
        foreach($data as $key=>$value){
            $email = Email::find($key);

            if($email){

                $path = EMAIL_FILES.'/'.$email->id;
                removeDirectory($path);

                $email->delete();
                $count++;
            }

        }

        return back()->with('flash_message',"{$count} ".__('site.deleted'));
    }
}
