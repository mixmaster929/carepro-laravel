<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Invoice;
use App\Lib\HelperTrait;
use App\Order;
use App\OrderField;
use App\OrderForm;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrdersController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_orders');
        $keyword = $request->get('search');
        $perPage = 25;
        $params = $request->all();

        if (!empty($keyword)) {

            $orders = Order::where(function($query) use ($keyword) {
                $query->whereHas('user',function($query) use($keyword){
                    $query->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                })->orWhere('id','LIKE',"%{$keyword}%");
            });

        /*    $orders = Order::whereHas('user',function($query) use($keyword){
                $query->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            });*/
        } else {
            $orders = Order::latest();
        }

        $title = __('site.orders');
        //filter by status
        if(isset($params['status']) && $params['status'] != '' )
        {
            $orders = $orders->where('status',$params['status']);
            $title = orderStatus($params['status']).' '.$title;
        }

        if(isset($params['form']) && !empty($params['form'])){
            $orders= $orders->where('order_form_id',$params['form']);
        }

        //filter by min_date
        if(isset($params['min_date']) && $params['min_date'] != '' )
        {
            $orders = $orders->where('created_at','>=',$params['min_date']);
        }

        //filter by max_date
        if(isset($params['max_date']) && $params['max_date'] != '' )
        {
            $orders = $orders->where('created_at','<=',Carbon::parse($params['max_date'].' 23:59:59')->toDateTimeString());
        }



        $orders = $orders->paginate($perPage);

        unset($params['search'],$params['page'],$params['field_id'],$params['create']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;
        }

        return view('admin.orders.index', compact('orders','perPage','filterParams','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(OrderForm $orderForm)
    {
        $this->authorize('access','create_order');
        return view('admin.orders.create',compact('orderForm'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,OrderForm $orderForm)
    {
        $this->authorize('access','create_order');
        $requestData = $request->all();

  //      Order::create($requestData);
        $messages=[];

        $rules = [
            'user_id'=>'required',
            'status'=>'required',
         //   'order_form_id'=>'required'
        ];

            foreach($orderForm->orderFields()->where('type','!=','label')->get() as $field){

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

                if($field->required==1){
                    $messages['field_'.$field->id.'.required'] = __('validation.required',['attribute'=>$field->name]);
                }

            }






        $this->validate($request,$rules,$messages);
        $requestData = $request->all();

        //First create order
        $order=  $orderForm->orders()->create($requestData);

        //sync candidates
        if(!empty($requestData['candidates'])){
            $order->candidates()->attach($requestData['candidates']);
        }


        //now save custom fields

        $customValues = [];
        //attach custom values


        foreach($orderForm->orderFields as $field) {
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


        $order->orderFields()->sync($customValues);

        if($request->invoice==1){
          try{
              if(empty($orderForm->invoice_amount)){
                  flashMessage(__('site.invoice-error'));
              }
              else{
                  $invoice=  $this->createInvoice($order->user_id,$orderForm->invoice_amount,$orderForm->invoice_title,$orderForm->invoice_description,null,null,$orderForm->invoice_category_id);
                  $order->invoices()->attach($invoice->id);
              }

          }
         catch (\Exception $exception){
              flashMessage($exception->getMessage());
         }

        }

        return redirect('admin/orders')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_order');
        $order = Order::findOrFail($id);
        $employer = $order->user;
        return view('admin.orders.show', compact('order','employer'));
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
        $this->authorize('access','edit_order');
        $order = Order::findOrFail($id);
        $orderForm = $order->orderForm;
        return view('admin.orders.edit', compact('order','orderForm'));
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
        $this->authorize('access','edit_order');

        $rules = [
            'user_id'=>'required',
            'status'=>'required',
        ];

        $order = Order::findOrFail($id);
        $messages=[];
        foreach($order->orderForm->orderFields()->where('type','!=','label')->get() as $field){

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

            if($field->required==1){
                $messages['field_'.$field->id.'.required'] = __('validation.required',['attribute'=>$field->name]);
            }
        }

        $this->validate($request,$rules,$messages);

        $requestData = $request->all();

        $password= $request->password;
        if(!empty($password)){
            $requestData['password'] = Hash::make($password);
        }
        else{
            unset($requestData['password']);
        }


        $order->update($requestData);

        $order->candidates()->sync($request->candidates);

        //now save custom fields

        $customValues = [];
        //attach custom values
        foreach($order->orderForm->orderFields as $field){
            if (isset($requestData['field_' . $field->id]) || $field->type=='file') {
                if ($field->type == 'file') {
                    if ($request->hasFile('field_' . $field->id)) {
                        //get current file name
                        if ($order->orderFields()->where('id', $field->id)->first()) {
                            $fileName = $order->orderFields()->where('id', $field->id)->first()->pivot->value;
                            @unlink($fileName);
                        }
                        //generate name for file

                        $name = $_FILES['field_' . $field->id]['name'];

                        $extension = $request->{'field_' . $field->id}->extension();

                        $name = str_ireplace('.' . $extension, '', $name);

                        $name = $order->id . '_' . time() . '_' . safeUrl($name) . '.' . $extension;

                        $path = $request->file('field_' . $field->id)->storeAs(EMPLOYER_FILES, $name, 'public_uploads');

                        $file = UPLOAD_PATH . '/' . $path;
                        $customValues[$field->id] = ['value' => $file];
                    } elseif ($order->orderFields()->where('id', $field->id)->first()) {
                        $fileName = $order->orderFields()->where('id', $field->id)->first()->pivot->value;
                        $customValues[$field->id] = ['value' => $fileName];
                    }
                } else {
                    $customValues[$field->id] = ['value' => $requestData['field_' . $field->id]];
                }
            }

        }

        $order->orderFields()->sync($customValues);

        if($request->notify==1){
            $this->sendEmail($order->user->email,__('site.order-updated'),__('site.order-updated-msg',['orderID'=>$order->id,'status'=>orderStatus($order->status)]));
        }

        return redirect('admin/orders')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_order');
        Order::destroy($id);

        return redirect('admin/orders')->with('flash_message', __('site.record-deleted'));
    }


    public function removeFile($fieldId,$orderId){
        $this->authorize('access','edit_order');
        $order = Order::find($orderId);
        $file = $order->orderFields()->where('id',$fieldId)->first()->pivot->value;
        @unlink($file);
        $order->orderFields()->detach($fieldId);
        return back()->with('flash_message',__('site.file').' '.__('site.deleted'));
    }

    public function createOrderInvoice(Request $request,Order $order){
        $this->authorize('access','create_invoice');
        $this->validate($request,[
           'title'=>'required',
            'amount'=>'required|numeric',
        ]);

        $requestData = $request->all();
        $requestData['user_id'] = $order->user_id;
       // $invoice = Invoice::create($requestData);
        $invoice = $this->createInvoice($order->user_id,$request->amount,$request->title,$request->description,null,$request->payment_method_id,$request->invoice_category_id);
        $order->invoices()->attach($invoice->id);

        return back()->with('flash_message',__('site.changes-saved'));

    }

    public function doCreate(Request $request){
        $this->validate($request,[
            'form'=>'required'
        ]);
        $form = $request->form;
        return redirect()->route('admin.orders.create',['orderForm'=>$form]);
    }


}
