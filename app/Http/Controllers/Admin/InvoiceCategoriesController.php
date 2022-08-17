<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\InvoiceCategory;
use Illuminate\Http\Request;

class InvoiceCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_invoice_categories');

        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $invoicecategories = InvoiceCategory::latest()->paginate($perPage);
        } else {
            $invoicecategories = InvoiceCategory::orderBy('name')->paginate($perPage);
        }

        return view('admin.invoice-categories.index', compact('invoicecategories','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_invoice_category');
        return view('admin.invoice-categories.create');
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
        $this->authorize('access','create_invoice_category');

        $this->validate($request,[
            'name'=>'required'
        ]);

        $requestData = $request->all();
        
        InvoiceCategory::create($requestData);

        return redirect('admin/invoice-categories')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_invoice_category');
        $invoicecategory = InvoiceCategory::findOrFail($id);

        return view('admin.invoice-categories.show', compact('invoicecategory'));
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
        $this->authorize('access','edit_invoice_category');
        $invoicecategory = InvoiceCategory::findOrFail($id);

        return view('admin.invoice-categories.edit', compact('invoicecategory'));
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
        $this->authorize('access','edit_invoice_category');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();
        
        $invoicecategory = InvoiceCategory::findOrFail($id);
        $invoicecategory->update($requestData);

        return redirect('admin/invoice-categories')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_invoice_category');
        InvoiceCategory::destroy($id);

        return redirect('admin/invoice-categories')->with('flash_message', __('site.record-deleted'));
    }
}
