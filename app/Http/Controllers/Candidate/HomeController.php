<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Interview;
use App\Invoice;
use App\Candidate;
use App\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Employment;
use App\EmploymentComment;
use App\EmploymentCommentAttachment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Lib\HelperTrait;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{
    use HelperTrait;
    public function index(){

        $user = Auth::user();
        $output = [];
        $output['applicationTotal'] = $user->applications()->count();
        $output['invoiceTotal'] = $user->invoices()->count();
        $output['placementTotal'] = $user->candidate->employments()->count();
        $output['testAttempts'] = $user->userTests()->count();

        $output['tests'] = Test::where('status',1)->latest()->limit(7)->get();
        $output['invoices'] = $user->invoices()->latest()->limit(5)->get();
        $output['placements'] = $user->candidate->employments()->limit(5)->get();

        return view('candidate.index.index',$output);
    }

    public function applications(){
        $user= Auth::user();
        $perPage = 30;
        $applications = $user->applications()->latest()->paginate($perPage);
        return view('candidate.home.applications',compact('applications','perPage'));
    }


    public function placements(){
        $perPage= 30;
        $user = Auth::user();
        $employments = $user->candidate->employments()->latest()->paginate($perPage);
        return view('candidate.home.placements',compact('employments','perPage'));
    }

    public function view(Employment $employment){
        // $this->authorize('view',$employment);
        $candidate = $employment->candidate->user;
        $employer = $employment->employer->user;
        $msgId = Str::random(10);
        return view('candidate.home.view',compact('employment','candidate', 'employer', 'msgId'));
    }

    public function comments(Employment $employment){
        // $this->authorize('view',$employment);
        $comments = $employment->employmentComments()->latest()->paginate(30);
        return view('candidate.home.comments',compact('comments'));
    }

    public function addComment(Request $request,Employment $employment){
        Log::info("Request=>".$request);
        // $this->authorize('view',$employment);
        $this->validate($request,[
            'content'=>'required'
        ]);
        $userId = Auth::user()->id;
        $employmentComment=  $employment->employmentComments()->create([
            'user_id'=>$userId,
            'content'=>$request->post('content')
        ]);

        $messageId = $request->post('msg_id');

        //check for any attachments
        $path = '../storage/tmp/'.$messageId;

        //scan directory for files
        if(is_dir($path)){


            //$files = scandir($path);
            $files = array_diff(scandir($path), array('.', '..'));

            if(count($files) > 0){
                //check for directory
                $destDir = UPLOAD_PATH.'/'.COMMENT_ATTACHMENTS.'/'.$employmentComment->id;

                if(!is_dir($destDir)){
                    rmkdir($destDir);
                }

                foreach($files as $value){
                    $newName = $destDir.'/'.$value;
                    $oldName = $path.'/'.$value;
                    rename($oldName,$newName);
                    //attach record
                    $employmentComment->employmentCommentAttachments()->create([
                        'file_name'=>$value,
                        'file_path'=>$newName
                    ]);
                }
            }
            @rmdir($path);
        }

        $link = route('admin.employment-comments.index',['employment'=>$employment->id]);
        $subject = __('site.new-employment-comment');
        $message = __('site.new-placement-comment-msg',['name'=>Auth::user()->name,'employer'=>$employment->employer->user->name,'candidate'=>$employment->candidate->user->name,'comment'=>$request->post('content'),'link'=>$link]);


        $this->notifyAdmins($subject,$message,'view_employment');

        return back()->with('flash_message',__('site.comment-saved'));
    }

    public function downloadAttachment(EmploymentCommentAttachment $employmentCommentAttachment){
        // $this->authorize('view',$employmentCommentAttachment->employmentComment->employment);
        $path = $employmentCommentAttachment->file_path;

        header('Content-type: '.getFileMimeType($path));

// It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

// The PDF source is in original.pdf
        readfile($path);
        exit();
    }

    public function downloadAttachments(EmploymentComment $employmentComment){

        // $this->authorize('view',$employmentComment->employment);
        $zipname = __('site.attachments').'.zip';
        $zip = new \ZipArchive;
        $zip->open($zipname, \ZipArchive::CREATE);


        $deleteFiles = [];

        foreach ($employmentComment->employmentCommentAttachments as $row) {
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

    public function upload(Request $request,$id){


        $validator = Validator::make($request->all(), [
            'file'=>'file|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files')
        ]);

        if ($validator->fails()) {

            $errorString = implode(",",$validator->messages()->all());
            return response($errorString, 500)
                ->header('Content-Type', 'text/plain');
        }

        $name= safeFile($_FILES['file']['name']);
        $tmpName = $_FILES['file']['tmp_name'];



        //create temp dir
        $path = '../storage/tmp/'.$id;
        if(!is_dir($path)){
            rmkdir($path);
        }



        $newName = $path.'/'.$name;
        //movefile
        if(!rename($tmpName,$newName)){
            return response(__('site.upload-failed'), 500)
                ->header('Content-Type', 'text/plain');
        }

        echo $newName;

        //now upload file
    }


    public function removeUpload(Request $request,$id){

        $name = $request->name;
        $path = '../storage/tmp/'.$id.'/'.safeFile($name);
        unlink($path);
        echo 'done';
    }




    public function showImage(Request $request){
        $file = $request->file;

        return response()->file($file);

    }

    public function download(Request $request){
        $file = $request->file;
        return response()->download($file);
    }

    public function removeFile($fieldId){
        $user = Auth::user();
        $file = $user->candidateFields()->where('id',$fieldId)->first()->pivot->value;
        @unlink($file);
        $user->candidateFields()->detach($fieldId);
        return back()->with('flash_message',__('site.file').' '.__('site.deleted'));
    }


    public function removePicture(){
        $user = Auth::user();

        @unlink($user->candidate->picture);
        $user->candidate->picture = '';
        $user->candidate->save();
        return back()->with('flash_message',__('site.picture').' '.__('site.deleted'));
    }

    public function removeCv(){
        $user = Auth::user();

        @unlink($user->candidate->cv_path);
        $user->candidate->cv_path= '';
        $user->candidate->save();
        return back()->with('flash_message',__('site.file').' '.__('site.deleted'));
    }

    public function interviews(){
        $candidate_id = Auth::user()->candidate->id;
        $interviews = Candidate::findOrFail($candidate_id)->interviews;
        
        return view('candidate.interview.interviews', compact(['interviews']));
    }

    public function viewInterview(Interview $interview)
    {
        return view('candidate.interview.view',compact('interview'));
    }
}
