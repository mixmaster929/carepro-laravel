<?php

namespace App\Http\Controllers\Api\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\EmploymentResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\TestResource;
use App\Test;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request){

        $user = $request->user();
        $output = [];
        $output['applicationTotal'] = $user->applications()->count();
        $output['invoiceTotal'] = $user->invoices()->count();
        $output['placementTotal'] = $user->candidate->employments()->count();
        $output['testAttempts'] = $user->userTests()->count();

        $tests = Test::where('status',1)->latest()->limit(7)->get();
        $output['tests'] = TestResource::collection($tests);

        $invoices = $user->invoices()->latest()->limit(5)->get();
        $output['invoices'] = InvoiceResource::collection($invoices);

        $placements =  $user->candidate->employments()->limit(5)->get();
        $output['placements'] = EmploymentResource::collection($placements);

        return response()->json($output);
    }

    public function applications(Request $request){
        $user= $request->user();
        $perPage = 30;
        if ($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $applications = $user->applications()->latest()->paginate($perPage);
        return ApplicationResource::collection($applications);
    }


    public function placements(Request $request){
        $perPage= 30;
        if ($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $user = $request->user();
        $employments = $user->candidate->employments()->latest()->paginate($perPage);
        return EmploymentResource::collection($employments);
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
        $file = $user->candidateFields()->where('id',$fieldId)->first()->pivot->value;
        @unlink($file);
        $user->candidateFields()->detach($fieldId);
        return response()->json([
            'status'=>true,
            'message'=>__('site.file').' '.__('site.deleted')
        ]);
    }


    public function removePicture(Request $request){
        $user = $request->user();

        @unlink($user->candidate->picture);
        $user->candidate->picture = '';
        $user->candidate->save();
        return response()->json([
            'status'=>true,
            'message'=>__('site.picture').' '.__('site.deleted')
        ]);
    }

    public function removeCv(Request $request){
        $user = $request->user();

        @unlink($user->candidate->cv_path);
        $user->candidate->cv_path= '';
        $user->candidate->save();
        return response()->json([
            'status'=>true,
            'message'=>__('site.file').' '.__('site.deleted')
        ]);
    }


}
