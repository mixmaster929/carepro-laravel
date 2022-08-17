<?php

namespace App\Http\Controllers\Api\Employer;

use App\Employment;
use App\EmploymentComment;
use App\EmploymentCommentAttachment;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmploymentCommentResource;
use App\Http\Resources\EmploymentResource;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlacementController extends Controller
{

    use HelperTrait;

    public function placements(Request $request){
        $perPage = 30;
        if($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $employments = $request->user()->employer->employments()->latest()->paginate($perPage);
        return EmploymentResource::collection($employments);
    }

    public function view(Employment $employment){
        $this->authorize('view',$employment);
        return new EmploymentResource($employment);
    }

    public function comments(Employment $employment){
        $this->authorize('view',$employment);
        $comments = $employment->employmentComments()->latest()->paginate(30);
        return EmploymentCommentResource::collection($comments);
    }

    public function addComment(Request $request,Employment $employment){
        $this->authorize('view',$employment);
        $this->validate($request,[
            'content'=>'required'
        ]);
        $userId = $request->user()->id;
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
        $message = __('site.new-placement-comment-msg',['name'=>$request->user()->name,'employer'=>$employment->employer->user->name,'candidate'=>$employment->candidate->user->name,'comment'=>$request->post('content'),'link'=>$link]);


        $this->notifyAdmins($subject,$message,'view_employment');

        return response()->json([
            'status'=>true,
            'message'=>__('site.comment-saved')
        ]);

    }

    public function downloadAttachment(Request $request,EmploymentCommentAttachment $employmentCommentAttachment){
        $this->authorize('view',$employmentCommentAttachment->employmentComment->employment);
        $path = $employmentCommentAttachment->file_path;

        if($request->type=='info'){
            return response()->json([
                'content_type'=>getFileMimeType($path),
                'filename'=>basename($path),
            ]);
        }

        header('Content-type: '.getFileMimeType($path));

        // It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

        // The PDF source is in original.pdf
        readfile($path);
        exit();
    }

    public function downloadAttachments(EmploymentComment $employmentComment){

        $this->authorize('view',$employmentComment->employment);
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

    public function upload(Request $request){

        $validator = Validator::make($request->all(), [
            'file'=>'file|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files'),
            'id'=>'required'
        ]);

        if ($validator->fails()) {

            $errorString = implode(",",$validator->messages()->all());

            return response()->json([
                'status'=>false,
                'message'=>$errorString
            ]);

          //  return response($errorString, 500)->header('Content-Type', 'text/plain');
        }

        $id = $request->id;

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
            return response()->json([
                'status'=>false,
                'message'=>__('site.upload-failed')
            ]);

            // return response(__('site.upload-failed'), 500)->header('Content-Type', 'text/plain');
        }

        return response()->json([
            'status'=>true,
            'name'=>$newName
        ]);

        //echo $newName;

    }


    public function removeUpload(Request $request,$id){


        $name = $request->name;
      //  exit($name);
    //    exit('the name');
        $path = '../storage/tmp/'.$id.'/'.safeFile($name);
        unlink($path);
        return response()->json([
            'status'=>true
        ]);
    }


}
