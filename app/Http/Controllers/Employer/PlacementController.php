<?php

namespace App\Http\Controllers\Employer;

use App\Employment;
use App\EmploymentComment;
use App\EmploymentCommentAttachment;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class PlacementController extends Controller
{

    use HelperTrait;

    public function placements(){
        $perPage=20;
        $employments = Auth::user()->employer->employments()->latest()->paginate($perPage);
        return view('employer.placement.placements',compact('employments','perPage'));
    }

    public function view(Employment $employment){
        $this->authorize('view',$employment);
        $candidate = $employment->candidate->user;
        // dd($candidate);
         // added kvk
         $user = User::find($candidate->id);
         // dd($candidate);
         $kvk_flag = false;
         $niwo_flag = false;
         $KvK = $user->candidateFields()->where('name','KvK Handelsregister')->first()? $user->candidateFields()->where('name','KvK Handelsregister')->first()->pivot->value : "";
         $niwo = $user->candidateFields()->where('name','Eurovergunningsnummer')->first()? $user->candidateFields()->where('name','Eurovergunningsnummer')->first()->pivot->value : "";
         $apiKey = "l7194f0c28d6844efd8d4ae8ea83604836";
         $prodKvKApi = "https://api.kvk.nl/api/v1/zoeken?";
         $prodPayCheckedApi = "https://www.paychecked.nl/register/?Bedrijfsnaam=&Bedrijfsplaats=&KvK=";
         
         // PayChecked
         // $crawler = new Client();
         $crawler = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
 
         $crawler = $crawler->request('GET', $prodPayCheckedApi.$KvK);
         $result = NULL;
 
         $paychecked_flag = false;
         $crawler->filter('.total__header')->each(function ($node) use (&$result) {
             $result = $node->text();
         });
         // dd($result);
         if($result != "Aantal bedrijven: 0")
            $paychecked_flag = true;
 
         // KVK
         if($KvK){
            // $response = Http::get($prodKvKApi."apikey=".$apiKey."&kvkNummer=".$KvK);
            // if($response->status() == 200)
            // $kvk_flag = true;

            $url = $prodKvKApi."apikey=".$apiKey."&kvkNummer=".$KvK;

            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CAINFO, 'F:/cert/cacert.pem');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $data = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if(curl_errno($ch)) {
                $kvk_flag = false;
            } else {
                if($httpCode == 200)
                $kvk_flag = true;
                else
                $kvk_flag = false;
            }

            curl_close ($ch);
         }
         
         // Niwo
        if($niwo){
            $niwo_flag = true;
        }

        $msgId = Str::random(10);
        return view('employer.placement.view',compact('employment','candidate','msgId', 'kvk_flag', 'paychecked_flag', 'niwo_flag'));
    }

    public function comments(Employment $employment){
        $this->authorize('view',$employment);
        $comments = $employment->employmentComments()->latest()->paginate(30);
        return view('employer.placement.comments',compact('comments'));
    }

    public function addComment(Request $request,Employment $employment){
        $this->authorize('view',$employment);
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
        $this->authorize('view',$employmentCommentAttachment->employmentComment->employment);
        $path = $employmentCommentAttachment->file_path;

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

}
