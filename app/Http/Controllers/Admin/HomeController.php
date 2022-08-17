<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Order;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(){

        $output = [];
        $output['totalUsers'] = User::count();

        $output['totalSales'] = Order::count();
        $currentMonth = date('m');
        $output['monthSales'] = Order::whereRaw('MONTH(created_at) = ?',[$currentMonth])->count();
        $output['yearSales'] = Order::whereYear('created_at', date('Y'))->count();

        $output['currency'] = setting('general_currency_symbol');
        $months = array_map('getMonthStr', range(-7,0));

        $monthlySales = [];
        $monthlyCount = [];
        $monthlyCompCount = [];
        $monthlyCanCount = [];
        foreach(range(-7,0) as $offset){
            //get the
            $start= date("Y-m-d", strtotime("$offset months first day of this month"));
            $end = date("Y-m-d", strtotime("$offset months last day of this month"));
            $monthlySales[] = Invoice::where('paid',1)->whereDate('created_at','>=', $start)->whereDate('created_at','<=', $end)->sum('amount');
            $monthlyCount[] = Order::whereDate('created_at','>=', $start)->whereDate('created_at','<=', $end)->count();
            $monthlyCompCount[] = Order::where('status','c')->whereDate('created_at','>=', $start)->whereDate('created_at','<=', $end)->count();
            $monthlyCanCount[] = Order::where('status','x')->whereDate('created_at','>=', $start)->whereDate('created_at','<=', $end)->count();
        }

        $output['monthSaleData'] = json_encode($monthlySales);
        $output['monthSaleCount'] = json_encode($monthlyCount);
        $output['monthSaleCompCount'] = json_encode($monthlyCompCount);
        $output['monthSaleCanCount'] = json_encode($monthlyCanCount);
        $output['monthList']= json_encode($months);
        $output['controller'] = $this;
        $output['recentEmployers'] = User::where('role_id',2)->latest()->limit(8)->get();
        $output['recentInvoices'] = Invoice::latest()->limit(8)->get();
        $output['recentOrders'] = Order::latest()->limit(8)->get();


        return view('admin.home.index',$output);
    }



    public function showImage(Request $request){
        $file = $request->file;

        return response()->file($file);

    }

    public function download(Request $request){
        $file = $request->file;
        if(!file_exists($file)){
            return back()->with('flash_message',__('site.invalid-file'));
        }
        return response()->download($file);
    }

    public function search(Request $request){

        $keyword = $request->get('term');

        if(empty($keyword)){
            return response()->json([]);
        }

        $users = User::where('role_id','!=',1)->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->limit(1000)->get();

        $formattedUsers = [];

        foreach($users as $user){
            if($user->role_id==2){
                $role=__('site.employer');
            }
            else{
                $role= __('site.candidate');
            }

            if($request->get('format')=='number'){
                $formattedUsers[] = ['id'=>$user->id,'text'=>"{$user->name} ({$role}) <{$user->telephone}>"];
            }
            else{

                $formattedUsers[] = ['id'=>$user->id,'text'=>"{$user->name} ({$role}) <{$user->email}>"];
            }

        }


        // $formattedUsers['pagination']=['more'=>false];
        return response()->json($formattedUsers);
    }
}
