<?php

namespace App\Http\Controllers\Api\Account;

use App\BillingAddress;
use App\Country;
use App\Http\Controllers\Controller;
use App\Http\Resources\BillingAddressResource;
use App\Http\Resources\CountryResource;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingAddressController extends Controller
{

    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $billingAddresses = BillingAddress::where('user_id',$user->id)->paginate(20);
        return BillingAddressResource::collection($billingAddresses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function countries()
    {
        //  //get countries
        $countries = Country::get();
        return CountryResource::collection($countries);
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
        $userId = $request->user()->id;
        if($request->is_default==1){
            BillingAddress::where('user_id',$userId)->update(['is_default'=>0]);
        }

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        BillingAddress::create($data);

        return response()->json([
            'status'=>true,
            'message'=>__('site.address-created')
        ]);

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
        $userId = $request->user()->id;
        if($request->is_default==1){
            BillingAddress::where('user_id',$userId)->update(['is_default'=>0]);
        }
        $billingAddress->fill($request->all());
        $billingAddress->save();

        return response()->json([
            'status'=>true,
            'message'=>__('site.changes-saved')
        ]);


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

        return response()->json([
            'status'=>true,
            'message'=>__('site.deleted')
        ]);

    }

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

        return response()->json($output);


    }

}
