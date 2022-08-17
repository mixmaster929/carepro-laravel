<?php

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCommentResource;
use App\Http\Resources\OrderFormResource;
use App\Http\Resources\OrderResource;
use App\Lib\HelperTrait;
use App\Order;
use App\OrderComment;
use App\OrderCommentAttachment;
use App\OrderField;
use App\OrderForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use HelperTrait;

    public function forms(Request $request){
        $formList = OrderForm::where('enabled',1)->orderBy('sort_order')->limit(100);

        if($request->exists('shortlist')){

            $formList = $formList->where('shortlist',1);
        }

        $formList = $formList->get();

        //check if there is only one form
      /*  if($formList->count()==1){
            $form = $formList->first();
            return redirect()->route('order-form',['orderForm'=>$form->id]);
        }*/

        return OrderFormResource::collection($formList);

    }

    public function form(OrderForm $orderForm){
        if(empty($orderForm->enabled)){
            abort(404);
        }

        return new OrderFormResource($orderForm);

    }

    /*
     * Save order and add to cart
     */
    public function save(Request $request)
    {



        $orderForm = OrderForm::findOrFail($request->order_form);


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

        $order = $request->user()->orders()->create($requestData);

        //get array of candidates from request
        $cart = $request->cart;


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
        $this->notifyAdminsApi(__('site.new-order'),__('site.new-order-msg',[
            'name'=>$order->user->name,
            'count'=> $order->candidates()->count()
        ]));


        if($orderForm->auto_invoice==1){
            $invoice=  $this->createInvoice($order->user_id,$orderForm->invoice_amount,$orderForm->invoice_title,$orderForm->invoice_description,null,null,$orderForm->invoice_category_id);
            $order->invoices()->attach($invoice->id);
            //redirect to invoice payment

            return response()->json([
                'status'=>true,
                'route'=>'cart.pay',
                'hash'=>$invoice->hash
            ]);


        }

        return response()->json([
            'status'=>true,
            'route'=>'order.complete',
            'message'=>__('site.order-complete-text')
        ]);

    }


    public function orders(Request $request){

        $perPage = 30;
        if($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $orders = $request->user()->orders()->latest()->paginate($perPage);

        return OrderResource::collection($orders);
    }

    public function view(Order $order){
        $this->authorize('view',$order);
        return new OrderResource($order);
    }

    public function comments(Request $request,Order $order){
        $this->authorize('view',$order);
        $perPage = 30;
        if($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $comments = $order->orderComments()->latest()->paginate($perPage);
        return OrderCommentResource::collection($comments);
    }

    public function addComment(Request $request,Order $order){
        $this->authorize('view',$order);
        $this->validate($request,[
            'content'=>'required'
        ]);
        $userId = $request->user()->id;
        $order->orderComments()->create([
            'user_id'=>$userId,
            'content'=>$request->post('content')
        ]);

        $link = route('admin.order-comments.index',['order'=>$order->id]);
        $subject = __('site.new-order-comment');
        $message = __('site.new-order-comment-msg',['orderNo'=>$order->id,'name'=>$order->user->name,'comment'=>$request->post('content'),'link'=>$link]);


        $this->notifyAdmins($subject,$message,'view_order');

        return response()->json([
            'status'=>true,
            'message'=>__('site.comment-saved')
        ]);

    }



    public function downloadAttachment(Request $request,OrderCommentAttachment $orderCommentAttachment){
        $this->authorize('view',$orderCommentAttachment->orderComment->order);
        $path = $orderCommentAttachment->file_path;

        if($request->type=='info'){
            return response()->json([
                'content_type'=>getFileMimeType($path),
                'filename'=>basename($path),
            ]);
        }
        else{
            header('Content-type: '.getFileMimeType($path));
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            readfile($path);
            exit();
        }

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
