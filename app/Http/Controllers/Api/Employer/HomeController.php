<?php

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmploymentResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index(Request $request){
        //get orders
        $user = $request->user();
        $output = [];
        $output['orders'] = OrderResource::collection($user->orders()->latest()->limit(5)->get());
        $output['invoices'] = InvoiceResource::collection($user->invoices()->latest()->limit(5)->get());
        $output['placements'] = EmploymentResource::collection($user->employer->employments()->limit(5)->get());
        $output['invoiceTotal'] = $user->invoices()->count();
        $output['orderTotal'] = $user->orders()->count();
        $output['placementTotal'] = $user->employer->employments()->count();
        $output['interviewTotal'] = $user->interviews()->count();
        return response()->json($output);
    }

    public function showImage(Request $request){
        $file = $request->file;
        return response()->file($file);
    }

    public function download(Request $request){
        $file = $request->file;
        return response()->download($file);
    }

    public function removeFile(Request $request,$fieldId){
        $user = $request->user();
        $file = $user->employerFields()->where('id',$fieldId)->first()->pivot->value;
        @unlink($file);
        $user->employerFields()->detach($fieldId);
        return response()->json([
            'status'=>true,
            'message'=>__('site.file').' '.__('site.deleted')
        ]);
    }

}
