<?php
namespace App\Lib;

class PaymentMethods 
{

    public $invoice;
    public $view;
    public function __construct($invoice){
        $this->invoice = $invoice;
        $this->view = 'account.methods.'.$invoice->paymentMethod->code;
    }

    public function paypal(){

        $gateway = \Omnipay\Omnipay::create('PayPal_Rest');

        $gateway->initialize(array(
            'clientId' => trim(paymentSetting(1,'client_id')),
            'secret'   => trim(paymentSetting(1,'secret')),
            'testMode' => (paymentSetting(1,'mode') == 'sandbox'), // Or false when you are ready for live transactions
        ));

        $transaction = $gateway->authorize(array(
            'amount'        => number_format(floatval($this->invoice->amount), 2, '.', ''),
            'currency'      => trim(strtoupper(setting('general_currency_code'))),
            'description'   => $this->invoice->title,
            'returnUrl' => route('user.callback',['code'=>$this->invoice->paymentMethod->code]),
            'cancelUrl' => route('user.invoice.cart'),

        ));


        $response = $transaction->send();

       // dd('paypa;');
//        $session->transactionRef = $response->getTransactionReference();
        session()->put('paypal-ref',$response->getTransactionReference());


        if ($response->isRedirect()) {
            // Yes it's a redirect.  Redirect the customer to this URL:
            $redirectUrl = $response->getRedirectUrl();
            return redirect($redirectUrl);
        }
        else{
            return redirect()->route('user.invoice.cart')->with('flash_message',__('site.unable-load'));

        }
    }



}