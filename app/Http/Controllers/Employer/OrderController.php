<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\JobRegion;
use App\Lib\HelperTrait;
use App\Order;
use App\OrderComment;
use App\OrderCommentAttachment;
use App\OrderField;
use App\OrderForm;
use App\User;
use App\Employment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Interview;
use App\Mail\InterviewAlert;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    use HelperTrait;



    public function forms(Request $request){
        $formList = OrderForm::where('enabled',1)->orderBy('sort_order')->limit(100);
        $title = __('site.order-forms');
        if($request->exists('shortlist')){
            $title = __('site.shortlist').' '.__('site.order-forms');
            $formList = $formList->where('shortlist',1);
        }

        $formList = $formList->get();

        //check if there is only one form
        if($formList->count()==1){
            $form = $formList->first();
            return redirect()->route('order-form',['orderForm'=>$form->id]);
        }

        return view('employer.order.forms',compact('formList','title'));
    }

    public function form(OrderForm $orderForm){
        if(empty($orderForm->enabled)){
            abort(404);
        }
        $cart = session()->get('cart');
        $regions = JobRegion::orderBy('sort_order')->get();
        return tview('employer.order.form',compact('cart','orderForm', 'regions'));
    }

    public function save(Request $request,OrderForm $orderForm)
    {
        $requestData = $request->all();

        


        $rules = [];
        foreach($orderForm->orderFieldGroups as $fieldGroup){
            foreach($fieldGroup->orderFields()->where('type','!=','label') as $field){

                if($field->type=='file'){
                    $required = '';
                    if($field->required==1){
                        $required = 'required|';
                    }

                    $rules['field_'.$field->id] = $required.'file|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files');
                }
                elseif($field->required==1){
                    $rules['field_'.$field->id] = 'required';
                }
            }

        }




        $this->validate($request,$rules);






        $requestData['status'] = 'p';

        $requestData['order_form_id'] = $orderForm->id;

        $order = Auth::user()->orders()->create($requestData);

        $cart = session()->get('cart');

        //sync candidates
        if (!empty($cart)) {
            $order->candidates()->attach($cart);
        }

        //now save custom fields
        $fields = OrderField::get();

        $customValues = [];
        //attach custom values

        foreach($orderForm->orderFieldGroups as $fieldGroup){
            foreach($fieldGroup->orderFields as $field){
            if (isset($requestData['field_' . $field->id])) {

                if ($field->type == 'file') {
                    if ($request->hasFile('field_' . $field->id)) {
                        //generate name for file

                        $name = $_FILES['field_' . $field->id]['name'];

                        //dd($name);


                        $extension = $request->{'field_' . $field->id}->extension();
                        //  dd($extension);

                        $name = str_ireplace('.' . $extension, '', $name);

                        $name = $order->id . '_' . time() . '_' . safeUrl($name) . '.' . $extension;

                        $path = $request->file('field_' . $field->id)->storeAs(EMPLOYER_FILES, $name, 'public_uploads');


                        $file = UPLOAD_PATH . '/' . $path;
                        $customValues[$field->id] = ['value' => $file];
                    }
                } else {
                    $customValues[$field->id] = ['value' => $requestData['field_' . $field->id]];
                }

            }


        }
    }

        $order->orderFields()->sync($customValues);

        //notify administrators
        $this->notifyAdmins(__('site.new-order'),__('site.new-order-msg',[
           'name'=>$order->user->name,
           'count'=> $order->candidates()->count()
        ]));
        //notify employers
        $this->sendEmail($order->user->email, __('site.new-order'),__('site.new-order-msg',[
            'name'=>$order->user->name,
            'count'=> $order->candidates()->count()
        ]));
        
        $region = $order->jobRegion;
        $emails = [];
        $users = DB::table('users')->where('role_id', 3)->get();
        if($region->id === 1){
            foreach($users as $user){
                $emails[] = $user->email;
            }
        }
        else{
            foreach($users as $user){
                $candidate_region = User::find($user->id)->candidateFields()->where('name','Actief in Regio')->first()? User::find($user->id)->candidateFields()->where('name','Actief in Regio')->first()->pivot->value : "";
                if($candidate_region === $region->name)
                $emails[] = $user->email;
            }
        }
        //notify employees
        $this->sendEmail($emails, __('site.new-order'),__('site.new-order-msg',[
            'name'=>$order->user->name,
            'count'=> $order->candidates()->count()
        ]));

        session()->remove('cart');
        if($orderForm->auto_invoice==1){
            $invoice=  $this->createInvoice($order->user_id,$orderForm->invoice_amount,$orderForm->invoice_title,$orderForm->invoice_description,null,null,$orderForm->invoice_category_id);
            $order->invoices()->attach($invoice->id);
            //redirect to invoice payment

            return redirect()->route('cart.pay',['hash'=>$invoice->hash]);
        }


        return redirect()->route('order.complete');
    }

    public function complete(){

        return tview('employer.order.complete');
    }

    public function orders(Request $request){

        $orders = Auth::user()->orders()->latest()->paginate(20);
        return view('employer.order.orders',compact('orders'));
    }

    public function view(Order $order){
        $this->authorize('view',$order);
        return view('employer.order.view',compact('order'));
    }

    public function offers(Order $order){
        $this->authorize('view',$order);
        $perPage = 25;
        
        $offers = DB::table('order_user')->where('order_id', '=', $order->id)->get();

        return view('employer.order.offer',compact('offers', 'order', 'perPage'));
    }

    public function allowOffer($id)
    {
        $offer = DB::table('order_user')->where('id', '=', $id)->first();
        $order = Order::find($offer->order_id);
        $order->bids()->syncWithPivotValues($offer->user_id, ['status' => 'allow']);
        // $order->update(['status' => 'i']);
        // $title = $offer->vacancy->title? $offer->vacancy->title : null;
        // $name = $application->user->name? $application->user->name : null;
        // $message = __('site.app_allowed',[
        //     'title'=>$title,
        //     'name'=> $name
        // ]);
        // $this->sendEmail($application->user->email, __('site.application'), $message);
        return redirect('employer/offers/'.$offer->order_id)->with('flash_message', __('Status changes'));
    }

    public function denyOffer($id)
    {
        $offer = DB::table('order_user')->where('id', '=', $id)->first();
        $order = Order::find($offer->order_id);
        $order->bids()->syncWithPivotValues($offer->user_id, ['status' => 'deny']);
        
        return redirect('employer/offers/'.$offer->order_id)->with('flash_message', __('Status changes'));
    }

    public function shortlist(Order $order, User $user, Request $request){
        $order->bids()->syncWithPivotValues($user->id, ['shortlisted' => $request->status]);

        return back()->with('flash_message',__('site.changes-saved'));
    }

    public function createPlacement(Request $request, Order $order, User $user){
        $employer = Auth::user();
        
        return view('employer.order.create-employment', compact(['order', 'employer', 'user']));
    }

    public function storePlacementt(Request $request){
        // dd($request->all());
        // $application_id = $request->application_id;
        // $application = Application::find($application_id);

        $this->validate($request,[
            'employer_user_id'=>'required',
            'user_id'=>'required',
            'start_date'=>'required',
            'active'=>'required'
        ]);

        $requestData = $request->all();
        $requestData['employer_id'] = User::find($requestData['employer_user_id'])->employer->id;
        $requestData['candidate_id'] = User::find($requestData['user_id'])->candidate->id;

        Employment::create($requestData);

        // $application->update(['status' => 'Placed']);
        $order = Order::find($request->order_id);
        $order->bids()->syncWithPivotValues($request->user_id, ['status' => 'Placed']);

        $employer = User::find($requestData['employer_user_id']);
        $candidate = User::find($requestData['user_id']);
        $end_date = $request->end_date? $request->end_date : '';

        $subject = __('site.make-placement');
        $message = __('site.notification_placement_to_users',[
            'candidate'=>$candidate->name,
            'employer'=> $employer->name,
            'start_date' => $requestData['start_date'],
            'end_date' => $end_date
        ]);
        $admin_message = __('site.notification_placement_to_admin',[
            'candidate'=>$candidate->name,
            'employer'=> $employer->name,
        ]);

        $this->sendEmail($employer->email, $subject, $message);
        $this->sendEmail($candidate->email, $subject, $message);
        $this->sendEmail(setting('general_admin_email'), $subject, $admin_message);
        
        // try{
        //     // Mail::to($interview->user->email)->send(New InterviewAlert($interview));
        //     Mail::to($application->user->email)->send(New InterviewAlert($interview));
        // }
        // catch(\Exception $ex){
        //     $this->warningMessage(__('site.mail-error').': '.$ex->getMessage());
        // }

        return redirect('employer/offers/'.$request->order_id)->with('flash_message', __('site.changes-saved'));
    }

    public function createInterview(Order $order, User $user){
        $employer = Auth::user();
        $candidate = $user;
        // dd($employer);
        return view('employer.order.interview-create', compact('order', 'employer', 'candidate'));
    }

    public function storeInterview(Request $request){
        // dd($request);

        $order_id = $request->order_id;

        $this->validate($request,[
            'user_id'=>'required',
            'interview_date'=>'required'
        ]);
        $requestData = $request->all();

        $requestData['hash'] = Str::random(30);

        $interview= Interview::create($requestData);
        
        //sync candidates
        if(!empty($request->candidate_id)){
            $interview->candidates()->attach($requestData['candidate_id']);
        }

        $order = Order::find($order_id);
        // $order->update(['status' => 'Interview Planned']);
        // dd($order);
        $order->bids()->syncWithPivotValues($request->candidate_id, ['status' => 'Interview Planned']);

        $candidate = User::find($request->candidate_id);

        //send mail to employer
        if($interview->reminder==1){
            try{
                // Mail::to($interview->user->email)->send(New InterviewAlert($interview));
                Mail::to($candidate->email)->send(New InterviewAlert($interview));
            }
            catch(\Exception $ex){
                $this->warningMessage(__('site.mail-error').': '.$ex->getMessage());
            }
        }

        return redirect('employer/offers/'.$order_id)->with('flash_message', __('site.changes-saved'));
    }

    public function offerComments($id){
        $offer = DB::table('order_user')->where('id', '=', $id)->first();
        $order = Order::find($offer->order_id);
        $user = User::find($offer->user_id);
        
        return view('employer.order.candidate-employer-comments',compact('user', 'order'));
    }

    public function comments(Order $order, User $user){
        $this->authorize('view',$order);
        $user_ids = [$user->id, $order->user_id];

        $comments = $order->orderComments()->whereIn('user_id', $user_ids)->latest()->paginate(30);

        return view('employer.order.comments',compact('comments'));
    }

    public function addComment(Request $request,Order $order){
        $this->authorize('view',$order);
        $this->validate($request,[
            'content'=>'required'
        ]);
        $userId = Auth::user()->id;
        $order->orderComments()->create([
            'user_id'=>$userId,
            'content'=>$request->post('content')
        ]);

        $link = route('admin.order-comments.index',['order'=>$order->id]);
        $subject = __('site.new-order-comment');
        $message = __('site.new-order-comment-msg',['orderNo'=>$order->id,'name'=>$order->user->name,'comment'=>$request->post('content'),'link'=>$link]);


        $this->notifyAdmins($subject,$message,'view_order');

        //notify canddiates
        $this->sendEmail(User::find($request->candidate_id)->email, __('site.new-comment'),__('site.new-comment-msg',[
            'user2' => User::find($request->candidate_id)->name,
            'user1' => $order->user->name,
            'content' => $request->post('content'),
        ]));

        return back()->with('flash_message',__('site.comment-saved'));
    }

    public function downloadAttachment(OrderCommentAttachment $orderCommentAttachment){
        $this->authorize('view',$orderCommentAttachment->orderComment->order);
        $path = $orderCommentAttachment->file_path;

        header('Content-type: '.getFileMimeType($path));

// It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

// The PDF source is in original.pdf
        readfile($path);
        exit();
    }

    public function downloadAttachments(OrderComment $orderComment){

        $this->authorize('view',$orderComment->order);
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



}
