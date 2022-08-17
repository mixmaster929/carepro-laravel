<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use App\Lib\HelperTrait;
use App\Lib\HelperTraitSaas;
use App\Lib\InvoiceApprover;
use App\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InvoicesController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_invoices');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
/*            $invoices = Invoice::latest()->where('id','LIKE',"%{$keyword}%")->orWhereHas('user',function($q) use ($keyword) {
                $q->whereRaw("match(name,email) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            })->orWhereRaw("match(extra) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);*/

            $invoices = Invoice::where(function($query) use ($keyword){
                $query->where('id','LIKE',"%{$keyword}%")->orWhereHas('user',function($q) use ($keyword) {
                    $q->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                })->orWhereRaw("match(title,description) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            });

        } else {

            $invoices = Invoice::latest();
        }
        

        $params = $request->all();

        //filter by min_date
        if(isset($params['min_date']) && $params['min_date'] != '' )
        {
            $invoices = $invoices->where('created_at','>=',$params['min_date']);
        }

        //filter by max_date
        if(isset($params['max_date']) && $params['max_date'] != '' )
        {
            $invoices = $invoices->where('created_at','<=',Carbon::parse($params['max_date'].' 23:59:59')->toDateTimeString());
        }

        //filter by status
        if(isset($params['status']) && $params['status'] != '' )
        {
            $invoices = $invoices->where('paid',$params['status']);
        }

        if(isset($params['user']) && $params['user'] != '' )
        {

            $invoices = $invoices->where('user_id',$params['user']);

        }

        if(isset($params['category']) && $params['category'] != '' )
        {

            $invoices = $invoices->where('invoice_category_id',$params['category']);

        }

        unset($params['search'],$params['page'],$params['field_id']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;
        }

        $invoice2= $invoices;

        $invoices = $invoices->paginate($perPage);

        $total = $invoice2->where('paid',1)->sum('amount');

        return view('admin.invoices.index', compact('invoices','total','filterParams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_invoice');
        return view('admin.invoices.create');
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
        $this->authorize('access','create_invoice');
        $rules = [
          'user_id'=>'required',
            'title'=>'required',
            'amount'=>'required',
            'due_date'=>'required'
        ];

        $this->validate($request,$rules);

        $requestData = $request->all();

        $notify=true;
        if(empty($request->notify)){
            $notify=false;
        }
 

      // $invoice= Invoice::create($requestData);
        $invoice = $this->createInvoice($request->user_id,$request->amount,$request->title,$request->description,$request->due_date,$request->payment_method_id,$request->invoice_category_id,$notify);
 


        return redirect('admin/invoices')->with('flash_message', __('site.changes-saved'));
    }


    public function approve(Invoice $invoice)
    {
        $this->authorize('access','approve_invoice');
        $invoice->paid = 1;
        $invoice->save();

        $title = __('site.invoice-approved');
        $message = __('site.invoice-approved-msg',['invoiceId'=>$invoice->id]);
        $this->sendEmail($invoice->user->email,$title,$message);

        return back()->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_invoice');
        $invoice = Invoice::findOrFail($id);
        $controller = $this;
        return view('admin.invoices.show', compact('invoice','controller'));
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
        $this->authorize('access','edit_invoice');
        $invoice = Invoice::findOrFail($id);



        return view('admin.invoices.edit', compact('invoice'));
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
        $this->authorize('access','edit_invoice');
        $rules = [
            'user_id'=>'required',
            'amount'=>'required',
            'title'=>'required',
            'due_date'=>'required'
        ];


        $this->validate($request,$rules);

        $requestData = $request->all();
        
        $invoice = Invoice::findOrFail($id);


        $invoice->update($requestData);

        return redirect('admin/invoices')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_invoice');
        Invoice::destroy($id);

        if(request('back')=='true'){
            return  back()->with('flash_message', __('site.record-deleted'));
        }

        if(request()->back == 1){
            return back()->with('flash_message', __('site.record-deleted'));
        }

        return redirect('admin/invoices')->with('flash_message', __('site.record-deleted'));
    }


}
