<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Lib\HelperTrait;
use App\Sms;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SmsController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_text_messages');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $sms = Sms::whereRaw("match(message,comment) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
        } else {
            $sms = Sms::latest();
        }

        $params = $request->all();
        //filter by min_date
        if(isset($params['min_date']) && $params['min_date'] != '' )
        {
            $sms = $sms->where('send_date','>=',$params['min_date']);
        }

        //filter by max_date
        if(isset($params['max_date']) && $params['max_date'] != '' )
        {
            $sms = $sms->where('send_date','<=',Carbon::parse($params['max_date'].' 23:59:59')->toDateTimeString());
        }

        if(isset($params['sent']) &&  $params['sent'] != '' ){
            $sms = $sms->where('sent',$params['sent']);
        }

        $sms = $sms->paginate($perPage);

        unset($params['search'],$params['page'],$params['sent']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;
        }


        return view('admin.sms.index', compact('sms','perPage','filterParams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_text_message');
        return view('admin.sms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('access','create_text_message');
        $this->validate($request,[
           'message'=>'required',
            'sms_gateway_id'=>'required'
        ]);

        $requestData = $request->all();

        if($request->sent==1){
            $requestData['send_date'] = Carbon::now()->toDateString();
        }
        
        $sms= Sms::create($requestData);


        //now attach users
        $sms->users()->attach($request->users);

        $sms->categories()->attach($request->categories);

        $recipients = [];

        //check if all candidates is selected
        if($request->all_candidates==1){

            foreach(User::where('role_id',3)->orderBy('id','desc')->cursor() as $user){
                $recipients[$user->telephone] = $user->telephone;
            }

        }

        if($request->all_employers==1){

            foreach(User::where('role_id',2)->orderBy('id','desc')->cursor() as $user){
                $recipients[$user->telephone] = $user->telephone;
            }

        }

        //now get numbers of users
        foreach($sms->users as $user){
            $recipients[$user->telephone] = $user->telephone;
        }


        //now get categories
        foreach($sms->categories as $category){
            foreach($category->candidates as $candidate){
                $recipients[$candidate->user->telephone] = $candidate->user->telephone;
            }
        }

        //now get plain numbers
        $numbers = newLinesToArray($request->telephone_numbers);

        foreach($numbers as $number){
            if(!empty($number)){
                $recipients[$number] = $number;
            }

        }

        foreach($recipients as $number){
            if(!empty($number)){
                $sms->smsRecipients()->create([
                    'telephone' =>$number
                ]);
            }

        }

        $msg = __('site.changes-saved');
        if($request->sent==1){
           $msg= $this->sendSavedSMS($sms);
        }


        return redirect('admin/sms')->with('flash_message', $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $this->authorize('access','view_text_message');
        $sm = Sms::findOrFail($id);

        return view('admin.sms.show', compact('sm'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $this->authorize('access','edit_text_message');

        $sm = Sms::findOrFail($id);

        if($sm->sent==1){
            abort(401);
        }

        return view('admin.sms.edit', compact('sm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->authorize('access','edit_text_message');
        $this->validate($request,[
            'message'=>'required',
            'sms_gateway_id'=>'required'
        ]);

        $requestData = $request->all();
        
        $sm = Sms::findOrFail($id);
        if($sm->sent==1){
            abort(401);
        }
        $sm->update($requestData);

        return redirect('admin/sms')->with('flash_message', __('site.changes-saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->authorize('access','delete_text_message');
        Sms::destroy($id);

        return redirect('admin/sms')->with('flash_message', __('site.record-deleted'));
    }


    public function deleteMultiple(Request $request){
        $this->authorize('access','delete_text_message');
        $data = $request->all();
        $count = 0;
        foreach($data as $key=>$value){
            $sms = Sms::find($key);

            if($sms){


                $sms->delete();
                $count++;
            }

        }

        return back()->with('flash_message',"{$count} ".__('site.deleted'));
    }

    public function sendNow(Sms $sms){
        $this->sendSavedSMS($sms);
        return back()->with('flash_message',__('site.message-sent'));
    }


}
