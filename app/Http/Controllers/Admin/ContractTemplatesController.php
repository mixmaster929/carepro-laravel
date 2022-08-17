<?php

namespace App\Http\Controllers\Admin;

use App\Contract;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\ContractTemplate;
use Illuminate\Http\Request;

class ContractTemplatesController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','manage_contract_templates');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $contracttemplates = ContractTemplate::where('name','LIKE','%'.$keyword.'%')->paginate($perPage);
        } else {
            $contracttemplates = ContractTemplate::latest()->paginate($perPage);
        }

        return view('admin.contract-templates.index', compact('contracttemplates','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','manage_contract_templates');
        return view('admin.contract-templates.create');
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
        $this->authorize('access','manage_contract_templates');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();

        ContractTemplate::create($requestData);

        return redirect('admin/contract-templates')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','manage_contract_templates');
        $contracttemplate = ContractTemplate::findOrFail($id);

        return view('admin.contract-templates.show', compact('contracttemplate'));
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
        $this->authorize('access','manage_contract_templates');
        $contracttemplate = ContractTemplate::findOrFail($id);

        return view('admin.contract-templates.edit', compact('contracttemplate'));
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
        $this->authorize('access','manage_contract_templates');
        $requestData = $request->all();

        $contracttemplate = ContractTemplate::findOrFail($id);
        $contracttemplate->update($requestData);

        return redirect('admin/contract-templates')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','manage_contract_templates');
        ContractTemplate::destroy($id);

        return redirect('admin/contract-templates')->with('flash_message', __('site.record-deleted'));
    }



}
