<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index(){
        //get orders
        $user = Auth::user();

        $output = [];
        $output['orders'] = Auth::user()->orders()->latest()->limit(5)->get();
        $output['invoices'] = Auth::user()->invoices()->latest()->limit(5)->get();
        $output['placements'] = Auth::user()->employer->employments()->limit(5)->get();

        $output['invoiceTotal'] = Auth::user()->invoices()->count();
        $output['orderTotal'] = Auth::user()->orders()->count();
        $output['placementTotal'] = Auth::user()->employer->employments()->count();
        $output['interviewTotal'] = Auth::user()->interviews()->count();

        return view('employer.index.index',$output);
    }

    public function showImage(Request $request){
        $file = $request->file;

        return response()->file($file);

    }

    public function download(Request $request){
        $file = $request->file;
        return response()->download($file);
    }

    public function removeFile($fieldId,$userId){
        $user = Auth::user();
        $file = $user->employerFields()->where('id',$fieldId)->first()->pivot->value;
        @unlink($file);
        $user->employerFields()->detach($fieldId);
        return back()->with('flash_message',__('site.file').' '.__('site.deleted'));
    }


}
