<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillingAddressResource;
use App\Http\Resources\InvoiceCategoryResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PaymentMethodResource;
use App\Invoice;
use App\InvoiceCategory;
use App\Lib\HelperTrait;
use App\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    use HelperTrait;

    public function index(Request $request){
        $invoices = $request->user()->invoices()->orderBy('id','desc')
            ->paginate(30);

        return InvoiceResource::collection($invoices);

    }

    public function view(Invoice $invoice){

        $this->authorize('view',$invoice);

        return new InvoiceResource($invoice);

    }

    public function invoiceCategories(){
        $invoiceCategories = InvoiceCategory::orderBy('sort_order')->get();
        return InvoiceCategoryResource::collection($invoiceCategories);
    }

    public function create(Request $request){

    }


}
