<?php

namespace App\Http\Controllers\Admin;

use App\Lib\HelperTrait;
use App\Order;
use App\OrderCommentAttachment;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\OrderComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderCommentsController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request,Order $order)
    {
        $this->authorize('access','view_order_comments');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $ordercomments = $order->orderComments()->whereRaw("match(content) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $ordercomments = $order->orderComments()->latest()->paginate($perPage);
        }

        return view('admin.order-comments.index', compact('ordercomments','perPage','order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Order $order)
    {
        $this->authorize('access','create_order_comment');
        $msgId = Str::random(10);
        return view('admin.order-comments.create',compact('order','msgId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,Order $order)
    {
        $this->authorize('access','create_order_comment');
        $requestData = $request->all();
        $requestData['user_id'] = Auth::user()->id;
        $requestData['content'] = saveInlineImages($requestData['content']);
        $orderComment= $order->orderComments()->create($requestData);

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
                $destDir = UPLOAD_PATH.'/'.COMMENT_ATTACHMENTS.'/'.$orderComment->id;

                if(!is_dir($destDir)){
                    rmkdir($destDir);
                }

                foreach($files as $value){
                    $newName = $destDir.'/'.$value;
                    $oldName = $path.'/'.$value;
                    rename($oldName,$newName);
                    //attach record
                    $orderComment->orderCommentAttachments()->create([
                        'file_name'=>$value,
                        'file_path'=>$newName
                    ]);
                }
            }
            @rmdir($path);
        }
        //create all recipients

        if($request->notify==1){
            $link = route('employer.view-order',['order'=>$order->id]).'?comment&'.getLoginToken($order->user_id);
            $subject = __('site.new-order-comment');
            $message = __('site.new-order-comment-msg',['orderNo'=>$order->id,'name'=>$orderComment->user->name,'comment'=>$requestData['content'],'link'=>$link]);
            $this->sendEmail($order->user->email,$subject,$message);
        }


        return redirect()->route('admin.order-comments.index',['order'=>$order->id])->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_order_comment');
        $ordercomment = OrderComment::findOrFail($id);

        return view('admin.order-comments.show', compact('ordercomment'));
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
        $this->authorize('access','edit_order_comment');
        $ordercomment = OrderComment::findOrFail($id);
        $msgId = Str::random(10);

        return view('admin.order-comments.edit', compact('ordercomment','msgId'));
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
        $this->authorize('access','edit_order_comment');
        $requestData = $request->all();
        $requestData['content'] = saveInlineImages($requestData['content']);

        $ordercomment = OrderComment::findOrFail($id);
        $ordercomment->update($requestData);

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
                $destDir = UPLOAD_PATH.'/'.COMMENT_ATTACHMENTS.'/'.$ordercomment->id;

                if(!is_dir($destDir)){
                    rmkdir($destDir);
                }

                foreach($files as $value){
                    $newName = $destDir.'/'.$value;
                    $oldName = $path.'/'.$value;
                    rename($oldName,$newName);
                    //attach record
                    $ordercomment->orderCommentAttachments()->create([
                        'file_name'=>$value,
                        'file_path'=>$newName
                    ]);
                }
            }
            @rmdir($path);
        }
        //create all recipients


        return redirect()->route('admin.order-comments.index',['order'=>$ordercomment->order_id])->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_order_comment');
        $orderId = OrderComment::find($id)->order_id;
        OrderComment::destroy($id);

        return redirect()->route('admin.order-comments.index',['order'=>$orderId])->with('flash_message', __('site.record-deleted'));
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

    public function viewImage(OrderCommentAttachment $orderCommentAttachment){
        $file = $orderCommentAttachment->file_path;

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

    public function downloadAttachment(OrderCommentAttachment $orderCommentAttachment){
        $this->authorize('access','view_order_comment');
        $path = $orderCommentAttachment->file_path;

        header('Content-type: '.getFileMimeType($path));

// It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

// The PDF source is in original.pdf
        readfile($path);
        exit();
    }

    public function downloadAttachments(OrderComment $orderComment){
        $this->authorize('access','view_order_comment');
        $zipname = __('site.attachments').'.zip';
        $zip = new \ZipArchive;
        $zip->open($zipname, \ZipArchive::CREATE);


        $deleteFiles = [];

        foreach ($orderComment->orderCommentAttachments as $row) {
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

    public function deleteAttachment(OrderCommentAttachment $orderCommentAttachment){
        $this->authorize('access','edit_order_comment');
        @unlink($orderCommentAttachment->file_path);
        $orderCommentAttachment->delete();
        return back()->with('flash_message', __('site.record-deleted'));

    }

}
