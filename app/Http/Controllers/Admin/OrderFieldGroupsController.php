<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\OrderFieldGroup;
use App\OrderForm;
use Illuminate\Http\Request;

class OrderFieldGroupsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request,OrderForm $orderForm)
    {

        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $orderfieldgroups = $orderForm->orderFieldGroups()->latest()->paginate($perPage);
        } else {
            $orderfieldgroups = $orderForm->orderFieldGroups()->latest()->paginate($perPage);
        }

        return view('admin.order-field-groups.index', compact('orderfieldgroups','orderForm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(OrderForm $orderForm)
    {
        return view('admin.order-field-groups.create',compact('orderForm'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,OrderForm $orderForm)
    {
        $this->validate($request,[
            'name'=>'required',
            'sort_order'=>'integer'
        ]);
        $requestData = $request->all();

        $orderForm->orderFieldGroups()->create($requestData);

        return redirect()->route('admin.order-field-groups.index',['orderForm'=>$orderForm->id])->with('flash_message', __('site.changes-saved'));
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
        $orderfieldgroup = OrderFieldGroup::findOrFail($id);

        return view('admin.order-field-groups.show', compact('orderfieldgroup'));
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
        $orderfieldgroup = OrderFieldGroup::findOrFail($id);

        return view('admin.order-field-groups.edit', compact('orderfieldgroup'));
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
            'sort_order'=>'integer',
        ]);
        $requestData = $request->all();
        
        $orderfieldgroup = OrderFieldGroup::findOrFail($id);
        $orderfieldgroup->update($requestData);

        return redirect()->route('admin.order-field-groups.index',['orderForm'=>$orderfieldgroup->orderForm->id])->with('flash_message', __('site.changes-saved'));
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
        $orderFormId = OrderFieldGroup::find($id)->order_form_id;
        OrderFieldGroup::destroy($id);

        return redirect()->route('admin.order-field-groups.index',['orderForm'=>$orderFormId])->with('flash_message', __('site.record-deleted'));
    }
}
