<?php

namespace App\Http\Controllers\Admin;

use App\ContractTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Contract;
use App\Lib\HelperTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ContractsController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_contracts');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $contracts = Contract::where('name','LIKE','%'.$keyword.'%');
        } else {
            $contracts = Contract::latest();
        }
        $title = __('site.contracts').' ('.$contracts->count().')';

        if(isset($request->user_id) && User::find($request->user_id)){
            $userId = $request->user_id;
            $contracts->whereHas('users',function($q) use($userId){
                $q->where('id',$userId);
            });
            $title = __('site.contracts').': '.User::find($request->user_id)->name.' ('.$contracts->count().')';
        }

        $contracts = $contracts->paginate($perPage);
        return view('admin.contracts.index', compact('contracts','perPage','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_contract');
        return view('admin.contracts.create');
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
        $this->validate($request,[
            'name'=>'required',
            'users'=>'required'
        ]);
        $this->authorize('access','create_contract');
        $requestData = $request->all();

        $contract = Contract::create($requestData);
        $contract->users()->attach($request->users);

        return redirect('/admin/contracts/' . $contract->id . '/edit');
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

        $this->authorize('access','view_contract');
        $contract = Contract::findOrFail($id);

        return view('admin.contracts.show', compact('contract'));
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
        $this->authorize('access','edit_contract');
        $contract = Contract::findOrFail($id);

        $templates = ContractTemplate::orderBy('name')->limit(1000)->get();
        $users = $contract->users()->orderBy('name')->get();

        return view('admin.contracts.edit', compact('contract','templates','users'));
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
        $this->validate($request,[
            'name'=>'required',
            'users'=>'required'
        ]);
        $this->authorize('access','edit_contract');
        $requestData = $request->all();

        $contract = Contract::findOrFail($id);
        $contract->update($requestData);
        $contract->users()->sync($request->users);

        return redirect('admin/contracts')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_contract');
        Contract::destroy($id);

        return redirect('admin/contracts')->with('flash_message', __('site.record-deleted'));
    }

    public function sendContract(Contract  $contract){
        $this->authorize('access','send_contract');
        foreach ($contract->users()->wherePivot('signed',0)->get() as $user){
            //TODO : Add code for sending contract reminder
            $link = route('user.contract.show',['contract'=>$contract->id]).'?'.getLoginToken($user->id);
            $subject = __('site.contract-prompt-subj',['contract'=>$contract->name]);
            $mailLink = '<br/><br/><a href="'.$link.'">'.$link.'</a><br/><br/>';
            $message = __('site.contract-prompt-mail',['contract'=>$contract->name,'link'=>$mailLink,'description'=>$contract->description]);
            $this->sendEmail($user->email,$subject,$message);
        }

        flashMessage(__('site.message-sent'));
        return back();

    }

    public function download(Contract $contract){
        $this->authorize('access','view_contract');
        $html = $contract->content;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download(safeUrl($contract->name).'.pdf');
    }

    public function getTemplate(ContractTemplate $contractTemplate){
        return response()->json([
            'name'=>$contractTemplate->name,
            'content'=>$contractTemplate->content
        ]);
    }




}
