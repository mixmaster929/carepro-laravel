<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\OrderForm;
use Illuminate\Http\Request;

class OrderFormsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $orderforms = OrderForm::latest()->paginate($perPage);
        } else {
            $orderforms = OrderForm::latest()->paginate($perPage);
        }

        return view('admin.order-forms.index', compact('orderforms','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.order-forms.create');
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
            'enabled'=>'required'
        ]);
        $requestData = $request->all();
        if(empty($requestData['sort_order'])){
            $requestData['sort_order']=0;
        }
        OrderForm::create($requestData);

        return redirect('admin/order-forms')->with('flash_message', __('site.changes-saved'));
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
        $orderform = OrderForm::findOrFail($id);

        return view('admin.order-forms.show', compact('orderform'));
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
        $orderform = OrderForm::findOrFail($id);

        return view('admin.order-forms.edit', compact('orderform'));
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
            'enabled'=>'required'
        ]);
        $requestData = $request->all();
        if(empty($requestData['sort_order'])){
            $requestData['sort_order']=0;
        }
        $orderform = OrderForm::findOrFail($id);
        $orderform->update($requestData);

        return redirect('admin/order-forms')->with('flash_message', __('site.changes-saved'));
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
        OrderForm::destroy($id);

        return redirect('admin/order-forms')->with('flash_message', __('site.record-deleted'));
    }
}
