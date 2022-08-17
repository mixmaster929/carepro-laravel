<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\OrderField;
use App\OrderFieldGroup;
use Illuminate\Http\Request;

class OrderFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, OrderFieldGroup $orderFieldGroup)
    {

        $keyword = $request->get('search');
        $perPage = 25;

        $orderfields = $orderFieldGroup->orderFields()->orderBy('sort_order')->paginate($perPage);


        return view('admin.order-fields.index', compact('orderfields','orderFieldGroup'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(OrderFieldGroup $orderFieldGroup)
    {
        $sortOrder = $orderFieldGroup->orderFields()->count()+1;
        return view('admin.order-fields.create',compact('orderFieldGroup','sortOrder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,OrderFieldGroup $orderFieldGroup)
    {
        $this->validate($request,[
           'name'=>'required',
            'type'=>'required',
            'sort_order'=>'integer'
        ]);
        $requestData = $request->all();
        $requestData['order_field_group_id']= $orderFieldGroup->id;

        OrderField::create($requestData);

        return redirect()->route('admin.order-fields.index',['orderFieldGroup'=>$orderFieldGroup->id])->with('flash_message', __('site.changes-saved'));

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
        $orderfield = OrderField::findOrFail($id);

        return view('admin.order-fields.show', compact('orderfield'));
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
        $orderfield = OrderField::findOrFail($id);

        return view('admin.order-fields.edit', compact('orderfield'));
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
            'type'=>'required',
            'sort_order'=>'integer'
        ]);
        $requestData = $request->all();
        
        $orderfield = OrderField::findOrFail($id);
        $orderfield->update($requestData);

        return redirect()->route('admin.order-fields.index',['orderFieldGroup'=>$orderfield->order_field_group_id])->with('flash_message', __('site.changes-saved'));
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
        $groupId = OrderField::find($id)->order_field_group_id;
        OrderField::destroy($id);

       // return redirect('admin/order-fields')->with('flash_message', __('site.record-deleted'));

        return redirect()->route('admin.order-fields.index',['orderFieldGroup'=>$groupId])->with('flash_message', __('site.record-deleted'));

    }
}
