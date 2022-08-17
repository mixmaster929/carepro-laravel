<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Interview;
use App\Invoice;
use App\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index(){

        $user = Auth::user();
        $output = [];
        $output['applicationTotal'] = $user->applications()->count();
        $output['invoiceTotal'] = $user->invoices()->count();
        $output['placementTotal'] = $user->candidate->employments()->count();
        $output['testAttempts'] = $user->userTests()->count();

        $output['tests'] = Test::where('status',1)->latest()->limit(7)->get();
        $output['invoices'] = $user->invoices()->latest()->limit(5)->get();
        $output['placements'] = $user->candidate->employments()->limit(5)->get();

        return view('candidate.index.index',$output);
    }

    public function applications(){
        $user= Auth::user();
        $perPage = 30;
        $applications = $user->applications()->latest()->paginate($perPage);
        return view('candidate.home.applications',compact('applications','perPage'));
    }


    public function placements(){
        $perPage= 30;
        $user = Auth::user();
        $employments = $user->candidate->employments()->latest()->paginate($perPage);
        return view('candidate.home.placements',compact('employments','perPage'));
    }

    public function showImage(Request $request){
        $file = $request->file;

        return response()->file($file);

    }

    public function download(Request $request){
        $file = $request->file;
        return response()->download($file);
    }

    public function removeFile($fieldId){
        $user = Auth::user();
        $file = $user->candidateFields()->where('id',$fieldId)->first()->pivot->value;
        @unlink($file);
        $user->candidateFields()->detach($fieldId);
        return back()->with('flash_message',__('site.file').' '.__('site.deleted'));
    }


    public function removePicture(){
        $user = Auth::user();

        @unlink($user->candidate->picture);
        $user->candidate->picture = '';
        $user->candidate->save();
        return back()->with('flash_message',__('site.picture').' '.__('site.deleted'));
    }

    public function removeCv(){
        $user = Auth::user();

        @unlink($user->candidate->cv_path);
        $user->candidate->cv_path= '';
        $user->candidate->save();
        return back()->with('flash_message',__('site.file').' '.__('site.deleted'));
    }


}
