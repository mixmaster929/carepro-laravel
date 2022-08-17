<?php

namespace App\Http\Controllers\Account;


use App\Invoice;
use App\Lib\HelperTrait;
use App\Lib\PaymentMethods;
use App\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    use HelperTrait;
    public function __construct(){
       // $this->middleware('auth');
    }

    public function index(){
        $invoices = Auth::user()->invoices()->orderBy('id','desc')
                    ->paginate(30);

        return view('account.billing.invoices',[
            'invoices'=>$invoices
        ]);
    }

    public function view(Invoice $invoice){

        $this->authorize('view',$invoice);

        $address = $this->billingAddress();
        return view('account.billing.view-invoice',[
           'invoice'=>$invoice,
            'address'=>$address
        ]);
    }

    public function pay(Invoice $invoice){
        session(['invoice'=>$invoice->id]);
        return redirect()->route('user.invoice.cart');
    }

    public function cart(Request $request){

        $invoiceId = session()->get('invoice');
        $invoice = Invoice::find($invoiceId);

        if($invoice->paid == 1){
            session()->forget('invoice');
            return redirect()->route('user.billing.invoices');
        }

        $description = $invoice->title;

        $paymentMethods = [];


        //get global methods
        foreach(PaymentMethod::where('status',1)->orderBy('sort_order')->get() as $method){
            if (is_dir(PAYMENT_PATH.'/'.$method->code)){
                $paymentMethods[$method->id] = $method;
            }

        }


        return view('account.billing.cart',['invoice'=>$invoice,'description'=>$description,'paymentMethods'=>$paymentMethods]);
    }

    public function cancel(Request $request)
    {
        session()->forget('invoice');
        $this->successMessage(__('site.removed-cart'));
        return redirect()->route('home');
    }

    public function checkout(Request $request){

        //check if user has any billing address
        $user = Auth::user();
        $address = $this->billingAddress();
        $invoiceId = session()->get('invoice');
        $invoice = Invoice::find($invoiceId);

        if($invoice->paid == 1){
            session()->forget('invoice');
            return redirect()->route('user.billing.invoices');
        }



        //check if it is a free invoice
        if($invoice->amount== 0){
            $result = __('site.order-success');
            session()->forget('invoice');

            $this->approveInvoice($invoice);
            $this->successMessage($result);
            return redirect()->route('user.billing.invoices');
        }


        $description = $invoice->title;
        $output= [];
        $output['address']=$address;
        $output['description']=$description;
        $output['user'] = $user;
        $output['item'] = $description;
        $output['invoice'] = $invoice;
        $output['paymentMethod'] = $invoice->paymentMethod->name;

        $code = $invoice->paymentMethod->code;
        $output['code'] = $code;

        //include function file
        if(!$this->setFunctions()){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('user.invoice.cart');
        }

        if (!function_exists('carepro_pay')){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('user.invoice.cart');
        }

        return carepro_pay($output);



/*        if(method_exists($paymentMethods,$code)){

           return call_user_func(array($paymentMethods, $code));

        }
        else{
            $view = 'account.methods.'.$invoice->paymentMethod->code;

            return view($view,$output);
        }*/



    }

    public function selectAddress(){
        $addresses = Auth::user()->billingAddresses;

        return view('account.billing.select-address',['addresses'=>$addresses]);
    }

    public function setAddress($id){
        session()->put('billing_address',$id);
        return redirect()->route('user.invoice.checkout');
    }

    public function complete(){
        $user = Auth::user();

        session()->forget('invoice');

        return view('account.billing.complete');
    }

    public function setMethod(Request $request){
        $this->validate($request,[
            'method'=>'required|integer'
        ]);
        $requestData = $request->all();
        $invoiceId = session()->get('invoice');
        $invoice = Invoice::find($invoiceId);
        $invoice->payment_method_id = $requestData['method'];
        $invoice->save();
        return redirect()->route('user.invoice.checkout');
    }


    private function setFunctions($code=null){
        if (!$code){
            $invoiceId = session()->get('invoice');
            $invoice = Invoice::find($invoiceId);
            $code = $invoice->paymentMethod->code;
        }

        $file = 'gateways/payment/'.$code.'/functions.php';
        if (file_exists($file)){
            require_once($file);
            return true;
        }
        else{
            return false;
        }
    }

    public function ipn(Request $request,$code){
        $this->setFunctions($code);
        if (!function_exists('carepro_ipn')){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('user.invoice.cart');
        }
        return carepro_ipn();
    }

    public function method(Request $request,$code,$function){
        $this->setFunctions($code);
        if (!function_exists($function)){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('user.invoice.cart');
        }
        return $function();
    }

}
