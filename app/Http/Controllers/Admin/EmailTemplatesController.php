<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_email_templates');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $emailtemplates = EmailTemplate::latest()->paginate($perPage);
        } else {
            $emailtemplates = EmailTemplate::latest()->paginate($perPage);
        }

        return view('admin.email-templates.index', compact('emailtemplates','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_email_template');
        return view('admin.email-templates.create');
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
        $this->authorize('access','create_email_template');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();

        $requestData['message'] = saveInlineImages($requestData['message']);

        EmailTemplate::create($requestData);

        return redirect('admin/email-templates')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_email_template');
        $emailtemplate = EmailTemplate::findOrFail($id);

        return view('admin.email-templates.show', compact('emailtemplate'));
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
        $this->authorize('access','edit_email_template');
        $emailtemplate = EmailTemplate::findOrFail($id);

        return view('admin.email-templates.edit', compact('emailtemplate'));
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
        $this->authorize('access','edit_email_template');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();
        $requestData['message'] = saveInlineImages($requestData['message']);

        $emailtemplate = EmailTemplate::findOrFail($id);
        $emailtemplate->update($requestData);

        return redirect('admin/email-templates')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_email_template');
        EmailTemplate::destroy($id);

        return redirect('admin/email-templates')->with('flash_message', __('site.record-deleted'));
    }

    public function getTemplate(EmailTemplate $emailTemplate){
        return response()->json([
           'subject'=>$emailTemplate->subject,
            'message'=>$emailTemplate->message
        ]);
    }
}
