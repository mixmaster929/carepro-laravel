<?php

namespace App\Http\Controllers\Admin;

use App\Employment;
use App\EmploymentCommentAttachment;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\EmploymentComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmploymentCommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request,Employment $employment)
    {
        $this->authorize('access','view_employment_comments');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $employmentcomments = $employment->employmentComments()->whereRaw("match(content) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $employmentcomments = $employment->employmentComments()->latest()->paginate($perPage);
        }

        return view('admin.employment-comments.index', compact('employmentcomments','perPage','employment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Employment $employment)
    {
        $this->authorize('access','create_employment_comment');
        $msgId = Str::random(10);
        return view('admin.employment-comments.create',compact('employment','msgId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,Employment $employment)
    {
        $this->authorize('access','create_employment_comment');
        $this->validate($request,[
           'content'=>'required'
        ]);
        $requestData = $request->all();
        $requestData['user_id'] = Auth::user()->id;
        $requestData['content'] = saveInlineImages($requestData['content']);
        $employmentComment= $employment->employmentComments()->create($requestData);

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

        //notify employer
        if($request->notify==1){
            $link = route('employer.view-placement',['employment'=>$employment->id]).'?comment&'.getLoginToken($employment->employer->user_id);
            $subject = __('site.new-placement-comment');
            $message = __('site.new-placement-comment-msg',['name'=>$employmentComment->user->name,'employer'=>$employment->employer->user->name,'candidate'=>$employment->candidate->user->name,'comment'=>$requestData['content'],'link'=>$link]);
            $this->sendEmail($employment->employer->user->email,$subject,$message);
        }


        return redirect()->route('admin.employment-comments.index',['employment'=>$employment->id])->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_employment_comment');
        $employmentcomment = EmploymentComment::findOrFail($id);

        return view('admin.employment-comments.show', compact('employmentcomment'));
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
        $this->authorize('access','edit_employment_comment');
        $employmentcomment = EmploymentComment::findOrFail($id);
        $msgId = Str::random(10);

        return view('admin.employment-comments.edit', compact('employmentcomment','msgId'));
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
        $this->authorize('access','edit_employment_comment');
        $this->validate($request,[
            'content'=>'required'
        ]);
        $requestData = $request->all();
        $requestData['content'] = saveInlineImages($requestData['content']);

        $employmentcomment = EmploymentComment::findOrFail($id);
        $employmentcomment->update($requestData);

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
                $destDir = UPLOAD_PATH.'/'.COMMENT_ATTACHMENTS.'/'.$employmentcomment->id;

                if(!is_dir($destDir)){
                    rmkdir($destDir);
                }

                foreach($files as $value){
                    $newName = $destDir.'/'.$value;
                    $oldName = $path.'/'.$value;
                    rename($oldName,$newName);
                    //attach record
                    $employmentcomment->employmentCommentAttachments()->create([
                        'file_name'=>$value,
                        'file_path'=>$newName
                    ]);
                }
            }
            @rmdir($path);
        }
        //create all recipients


        return redirect()->route('admin.employment-comments.index',['employment'=>$employmentcomment->employment_id])->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_employment_comment');
        $employmentId = EmploymentComment::find($id)->employment_id;
        EmploymentComment::destroy($id);

        return redirect()->route('admin.employment-comments.index',['employment'=>$employmentId])->with('flash_message', __('site.record-deleted'));
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

    public function viewImage(EmploymentCommentAttachment $employmentCommentAttachment){
        $file = $employmentCommentAttachment->file_path;

        if (file_exists($file))
        {
            $size = getimagesize($file);

            $fp = fopen($file, 'rb');

            if ($size and $fp)
            {
                header('Content-Type: '.$size['mime']);
                header('Content-Length: '.filesize($file));

                fpassthru($fp);

                exit;
            }
        }
    }

    public function downloadAttachment(EmploymentCommentAttachment $employmentCommentAttachment){
        $this->authorize('access','view_employment_comment');
        $path = $employmentCommentAttachment->file_path;

        header('Content-type: '.getFileMimeType($path));

// It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

// The PDF source is in original.pdf
        readfile($path);
        exit();
    }

    public function downloadAttachments(EmploymentComment $employmentComment){
        $this->authorize('access','view_employment_comment');
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

    public function deleteAttachment(EmploymentCommentAttachment $employmentCommentAttachment){
        $this->authorize('access','edit_employment_comment');
        @unlink($employmentCommentAttachment->file_path);
        $employmentCommentAttachment->delete();
        return back()->with('flash_message', __('site.record-deleted'));

    }

}
