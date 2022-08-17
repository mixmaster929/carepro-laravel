<?php

namespace App\Http\Controllers\Account;

use App\BillingAddress;
use App\Country;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BillingAddressController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $billingAddresses = BillingAddress::where('user_id',$user->id)->paginate(20);
        return view('account.account.addresses',['addresses'=>$billingAddresses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  //get countries
        $countries = Country::all(['id','name','currency_code']);
        $countryList = [];
        foreach($countries as $row){
            $countryList[$row->id]=$row->name;
        }

        $output = [
          'title'=>'Create Address',
            'route'=>'billing-address.store',
            'countries'=>$countryList
        ];

        return view('account.account.add-address',$output);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->getValidator($request);
        //check if is default and update all to zero
        $userId = Auth::user()->id;
        if($request->is_default==1){
            BillingAddress::where('user_id',$userId)->update(['is_default'=>0]);
        }

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $model = BillingAddress::create($data);
        session()->put('billing_address',$model->id);
        $this->successMessage(__('site.address-created'));
        if(session()->exists('invoice')){
            return redirect()->route('user.invoice.checkout');
        }
        else{
            return redirect()->route('user.billing-address.index');
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BillingAddress  $billingAddress
     * @return \Illuminate\Http\Response
     */
    public function show(BillingAddress $billingAddress)
    {

    }

    private function getValidator($request){
        $this->validate($request,[
            'name'=>'required',
            'address'=>'required',
            'city'=>'required',
            'state'=>'required',
            'country_id'=>'required',
            'phone'=>'required|max:16'
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BillingAddress  $billingAddress
     * @return \Illuminate\Http\Response
     */
    public function edit(BillingAddress $billingAddress)
    {
        $this->authorize('update',$billingAddress);

        $countries = Country::all(['id','name','currency_code']);
        $countryList = [];
        foreach($countries as $row){
            $countryList[$row->id]=$row->name;
        }

        $output = [
            'title'=>__('site.edit-address'),
            'route'=>'user.billing-address.update',
            'countries'=>$countryList
        ];
        $billingdata = $billingAddress->toArray();
        $output = array_merge($output,$billingdata);



        return view('account.account.edit-address',$output);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BillingAddress  $billingAddress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillingAddress $billingAddress)
    {
        $this->authorize('update',$billingAddress);
        $this->getValidator($request);
        $userId = Auth::user()->id;
        if($request->default==1){
            BillingAddress::where('user_id',$userId)->update(['default'=>0]);
        }
        $billingAddress->fill($request->all());
        $billingAddress->save();
        $this->successMessage(__('site.changes-saved'));
        if(session()->exists('invoice')){
            return redirect()->route('user.invoice.checkout');
        }
        else{
            return redirect()->route('user.billing-address.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BillingAddress  $billingAddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,BillingAddress $billingAddress)
    {
        $this->authorize('delete',$billingAddress);
        $billingAddress->delete();
        $this->warningMessage(__('admin.deleted'));

        return redirect()->route('billing-address.index');
    }

}
