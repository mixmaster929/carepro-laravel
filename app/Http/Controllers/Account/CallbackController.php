<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Unirest\Request\Body;

class CallbackController extends Controller
{
    use HelperTrait;
    public $invoice;
    public function method($code){
        $this->setFunctions($code);
        if (!function_exists('carepro_callback')){
            flashMessage(__lang('invalid-gateway'));
            return redirect()->route('user.invoice.cart');
        }
        return carepro_callback();
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

    public function paypal(){
       /* $session = new Container('paypal');

        $this->loadValues('paypal');*/
        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->initialize(array(
            'clientId' => trim(paymentSetting(1,'client_id')),
            'secret'   => trim(paymentSetting(1,'secret')),
            'testMode' => (paymentSetting(1,'mode') == 'sandbox'), // Or false when you are ready for live transactionsor live transactions

        ));

        try {

            $transaction = $gateway->completePurchase(array(
                'payerId'             => request()->input('PayerID'),
                'transactionReference' =>  session()->get('paypal-ref')
            ));

            $finalResponse = $transaction->send();

            if ($finalResponse->isSuccessful()) {
                $results = $finalResponse->getTransactionReference();
                $msg=  $this->approveInvoice($this->invoice);
                $this->successMessage($msg);
                return redirect()->route('user.billing.invoices');

            }else{
                throw new \Exception(__('site.transaction-failed'));
            }

        } catch (\Exception $e) {
            $this->errorMessage(__('site.payment-unsuccessful').': '.$e->getMessage());
            return redirect()->route('user.invoice.cart');
        }

        return redirect()->route('user.invoice.cart');
    }

    public function stripe(){

        if(!request()->isMethod('post'))
        {

            return redirect()->route('user.invoice.cart');
        }

        $token  = request()->input('stripeToken');

        Stripe::setApiKey(paymentSetting(2,'secret_key'));

        try{

            $customer = Customer::create([
                'email'=>$this->invoice->user->email,
                'source'=>$token
            ]);

            $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount'   => ($this->invoice->amount * 100),
                'currency' => strtolower(trim(setting('general_currency_code')))
            ));

            $msg=  $this->approveInvoice($this->invoice);
            $this->successMessage($msg);
            return redirect()->route('user.billing.invoices');

        }
        catch(\Exception $ex){


            $this->errorMessage(__('site.payment-unsuccessful').$ex->getMessage());

            return redirect()->route('user.invoice.cart');
        }

    }

    public function paystack(){

        $tid = request()->input('paystack-trxref');
        $result = array();
//The parameter after verify/ is the transaction reference to be verified
        $url = 'https://api.paystack.co/transaction/verify/'.$tid;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.paymentSetting(5,'secret_key')]
        );
        $request = curl_exec($ch);
        if(curl_error($ch)){
            return redirect()->route('user.invoice.cart')->with('flash_message','error:' . curl_error($ch));
        }
        curl_close($ch);

        if ($request) {
            $result = json_decode($request, true);
        }

        if (array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success')) {

            $msg=  $this->approveInvoice($this->invoice);
            $this->successMessage($msg);
            return redirect()->route('user.billing.invoices');
        }else{
            $this->errorMessage(__('site.payment-unsuccessful'));

            return redirect()->route('user.invoice.cart');
        }
    }

    public function rave(){
        $response = request()->input('resp');
        $responsObj= json_decode($response);

        $ref = $responsObj->data->tx->txRef;



        $data = array('txref' => $ref,
            'SECKEY' => paymentSetting(6,'secret_key'), //secret key from pay button generated on rave dashboard
            'include_payment_entity' => 1
        );




        if(paymentSetting(6,'mode')=='test'){
            $endPoint = 'https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify';
        }
        else{
            $endPoint = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify';
        }


        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = Body::json($data);


        try{

            $response = \Unirest\Request::post($endPoint, $headers, $body);



            //check the status is success
            if ($response->body->status === "success" && $response->body->data->chargecode === "00") {

                $msg=  $this->approveInvoice($this->invoice);
                $this->successMessage($msg);
                return redirect()->route('user.billing.invoices');
            }
            else{
                $this->errorMessage(__('site.payment-unsuccessful'));

                return redirect()->route('user.invoice.cart');

            }


        }
        catch(\Exception $ex){
            $this->errorMessage(__('site.payment-unsuccessful').$ex->getMessage());

            return redirect()->route('user.invoice.cart');
        }

    }

    public function twocheckout(){


        $hashSecretWord = trim(paymentSetting(3,'secret_word')); //2Checkout Secret Word
        $hashSid = trim(paymentSetting(3,'account_number')); //2Checkout account number
        $hashTotal = number_format(floatval($this->invoice->amount), 2, '.', ''); //Sale total to validate against
        $hashOrder = request()->input('order_number'); //2Checkout Order Number
        $StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));
        if ($StringToHash != request()->input('key') || request()->input('credit_card_processed') != 'Y' ) {
            $this->errorMessage(__('site.payment-unsuccessful'));
            return redirect()->route('user.invoice.cart');

        } else {
            $msg=  $this->approveInvoice($this->invoice);
            $this->successMessage($msg);
            return redirect()->route('user.billing.invoices');

        }

    }

}
