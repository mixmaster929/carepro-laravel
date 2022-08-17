<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\SmsTemplate;
use Illuminate\Http\Request;

class SmsTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_sms_templates');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $smstemplates = SmsTemplate::latest()->paginate($perPage);
        } else {
            $smstemplates = SmsTemplate::latest()->paginate($perPage);
        }

        return view('admin.sms-templates.index', compact('smstemplates','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_sms_template');
        return view('admin.sms-templates.create');
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
        $this->authorize('access','create_sms_template');
        $this->validate($request,[
            'name'=>'required',
            'message'=>'required'
        ]);
        $requestData = $request->all();
        
        SmsTemplate::create($requestData);

        return redirect('admin/sms-templates')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_sms_template');
        $smstemplate = SmsTemplate::findOrFail($id);

        return view('admin.sms-templates.show', compact('smstemplate'));
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
        $this->authorize('access','edit_sms_template');
        $smstemplate = SmsTemplate::findOrFail($id);

        return view('admin.sms-templates.edit', compact('smstemplate'));
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
        $this->authorize('access','edit_sms_template');
        $this->validate($request,[
            'name'=>'required',
            'message'=>'required'
        ]);
        $requestData = $request->all();
        
        $smstemplate = SmsTemplate::findOrFail($id);
        $smstemplate->update($requestData);

        return redirect('admin/sms-templates')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_sms_template');
        SmsTemplate::destroy($id);

        return redirect('admin/sms-templates')->with('flash_message', __('site.record-deleted'));
    }

    public function getTemplate(SmsTemplate $smsTemplate){
        return $smsTemplate->message;
    }
}
