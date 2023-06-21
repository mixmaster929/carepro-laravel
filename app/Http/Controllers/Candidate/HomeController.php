<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Interview;
use App\Invoice;
use App\Candidate;
use App\JobRegion;
use App\Test;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Employment;
use App\EmploymentComment;
use App\EmploymentCommentAttachment;
use App\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Lib\HelperTrait;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

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
        // dd($application->interviews()->latest()->first()->interview_date);
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
        $kvk_flag = false;
        $niwo_flag = false;
        $KvK = $employer->employerFields()->where('name', 'KvK nummer')->first()? $employer->employerFields()->where('name', 'KvK nummer')->first()->pivot->value : "";
        $apiKey = "l7194f0c28d6844efd8d4ae8ea83604836";
        $prodKvKApi = "https://api.kvk.nl/api/v1/zoeken?";
        $prodPayCheckedApi = "https://www.paychecked.nl/register/?Bedrijfsnaam=&Bedrijfsplaats=&KvK=";

        // PayChecked
        $crawler = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        $crawler = $crawler->request('GET', $prodPayCheckedApi.$KvK);
        $result = NULL;

        $paychecked_flag = false;
        $crawler->filter('.total__header')->each(function ($node) use (&$result) {
            $result = $node->text();
        });
        if($result != "Aantal bedrijven: 0")
            $paychecked_flag = true;

        // KVK
        if($KvK){
            $url = $prodKvKApi."apikey=".$apiKey."&kvkNummer=".$KvK;

            $ch = curl_init();
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
        if (!empty($niwo) && is_numeric($niwo)) {
            $niwo_flag = true;
        }

        return view('candidate.home.view',compact('employment','candidate', 'employer', 'msgId', 'kvk_flag', 'paychecked_flag', 'niwo_flag'));
    }

    public function comments(Employment $employment){
        // $this->authorize('view',$employment);
        $comments = $employment->employmentComments()->latest()->paginate(30);
        return view('candidate.home.comments',compact('comments'));
    }

    public function addComment(Request $request,Employment $employment){
        // Log::info("Request=>".$request);
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

    public function orders()
    {
        $candidate = Auth::user();
        $candidate_region = $candidate->candidateFields()->where('name','Actief in Regio')->first()? $candidate->candidateFields()->where('name','Actief in Regio')->first()->pivot->value : "";
        $region = JobRegion::where('name', $candidate_region)->firstOrFail();
        $allregion = JobRegion::where('name', "Alle Regio's")->firstOrFail();
        // $userId = $candidate->id;
        $orders = Order::whereIn('region_id', [$allregion->id, $region->id])->get();

        // $offers = Order::whereHas('bids', function($q) use($userId){
        //     $q->where('user_id',$userId);
        // })->where('region_id', $region->id)->get();
        // foreach($orders as $order){
        //     dd($order->bids);
        //     if(count($order->bids)>0 && Auth::user()->id === $order->bids[0]->pivot->user_id)
        //     dd($orders);
        // }
        return view('candidate.orders.index', compact('orders'));
    }

    public function orderView(Order $order)
    {
        $user = Auth::user();
        $user_ids = [$user->id, $order->user_id];

        $comments = $order->orderComments()->whereIn('user_id', $user_ids)->latest()->paginate(30);
        return view('candidate.orders.view', compact('order', 'comments'));
    }

    public function apply(Request $request){
        
        $order_id = $request->order_id;
        $amount = $request->amount;
        $user_id = Auth::user()->id;
        $order = Order::find($order_id);

        if(count($order->bids)>0 && ($user_id == $order->bids[0]->pivot->user_id) && $order->bids[0]->pivot->offer){
            $order->bids()->syncWithPivotValues($user_id, ['offer' => $amount]);

            //notify employers
            $this->sendEmail($order->user->email, __('site.edit-offer'),__('site.edit-offer-msg-employer',[
                'candidate' => Auth::user()->name,
                'amount' => $amount
            ]));
            //notify canddiates
            $this->sendEmail(Auth::user()->email, __('site.edit-offer'),__('site.edit-offer-msg-candidate',[
                'employer' => $order->user->name,
                'amount' => $amount
            ]));
        }
        else{
            $order->bids()->attach($user_id, ['offer' => $amount]);

            //notify administrators
            $this->notifyAdmins(__('site.new-offer'),__('site.new-offer-msg',[
                'name' => Auth::user()->name,
                'amount' => $amount,
                'employer' => $order->user->name
            ]));
            //notify employers
            $this->sendEmail($order->user->email, __('site.new-offer'),__('site.new-offer-msg-employer',[
                'candidate' => Auth::user()->name,
                'amount' => $amount
            ]));
            //notify canddiates
            $this->sendEmail(Auth::user()->email, __('site.new-offer'),__('site.new-offer-msg-candidate',[
                'employer' => $order->user->name,
                'amount' => $amount
            ]));
        }

        return redirect()->route('candidate.orders');
    }

    public function orderComments(Order $order){
        // $this->authorize('view',$employment);
        // $comments = $order->orderComments()->latest()->paginate(30);
        $user = Auth::user();
        $user_ids = [$user->id, $order->user_id];

        $comments = $order->orderComments()->whereIn('user_id', $user_ids)->latest()->paginate(30);
        return view('candidate.home.comments',compact('comments'));
    }

    public function orderAddComment(Request $request, Order $order, User $user){
        // dd($request);
        // Log::info("Request=>".$request);
        // $this->authorize('view',$employment);
        $this->validate($request,[
            'content'=>'required'
        ]);

        $candidate_id = $user->id;
        $employer_id = $order->user_id;

        $requestData['user_id'] = $candidate_id;
        $requestData['content'] = $request->content;
        $orderComment= $order->orderComments()->create($requestData);

        //notify employers
        $this->sendEmail($order->user->email, __('site.new-comment'),__('site.new-comment-msg',[
            'user1' => Auth::user()->name,
            'user2' => $order->user->name,
            'content' => $request->content,
        ]));
        // //notify canddiates
        // $this->sendEmail(Auth::user()->email, __('site.new-comment'),__('site.new-comment-msg',[
        //     'user2' => Auth::user()->name,
        //     'user1' => $order->user,
        //     'content' => $request->content,
        // ]));

        // $requestData['user_id'] = $employer_id;
        // $orderComment= $order->orderComments()->create($requestData);

        // $userId = Auth::user()->id;
        // $employmentComment=  $employment->employmentComments()->create([
        //     'user_id'=>$userId,
        //     'content'=>$request->post('content')
        // ]);

        // $messageId = $request->post('msg_id');

        // //check for any attachments
        // $path = '../storage/tmp/'.$messageId;

        // //scan directory for files
        // if(is_dir($path)){


        //     //$files = scandir($path);
        //     $files = array_diff(scandir($path), array('.', '..'));

        //     if(count($files) > 0){
        //         //check for directory
        //         $destDir = UPLOAD_PATH.'/'.COMMENT_ATTACHMENTS.'/'.$employmentComment->id;

        //         if(!is_dir($destDir)){
        //             rmkdir($destDir);
        //         }

        //         foreach($files as $value){
        //             $newName = $destDir.'/'.$value;
        //             $oldName = $path.'/'.$value;
        //             rename($oldName,$newName);
        //             //attach record
        //             $employmentComment->employmentCommentAttachments()->create([
        //                 'file_name'=>$value,
        //                 'file_path'=>$newName
        //             ]);
        //         }
        //     }
        //     @rmdir($path);
        // }

        // $link = route('admin.employment-comments.index',['employment'=>$employment->id]);
        // $subject = __('site.new-employment-comment');
        // $message = __('site.new-placement-comment-msg',['name'=>Auth::user()->name,'employer'=>$employment->employer->user->name,'candidate'=>$employment->candidate->user->name,'comment'=>$request->post('content'),'link'=>$link]);


        // $this->notifyAdmins($subject,$message,'view_employment');

        return back()->with('flash_message',__('site.comment-saved'));
    }
}
