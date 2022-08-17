<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiTokensController extends Controller
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
            $apitokens = ApiToken::latest()->paginate($perPage);
        } else {
            $apitokens = ApiToken::latest()->paginate($perPage);
        }

        return view('admin.api-tokens.index', compact('apitokens','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $string = Str::random(40);
        ApiToken::create([
            'token'=>$string,
            'enabled'=>1
        ]);

        return redirect('admin/api-tokens')->with('flash_message', __('site.changes-saved'));
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

        $requestData = $request->all();

        ApiToken::create($requestData);

        return redirect('admin/api-tokens')->with('flash_message', __('site.changes-saved'));
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
        $apitoken = ApiToken::findOrFail($id);

        return view('admin.api-tokens.show', compact('apitoken'));
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
        $apitoken = ApiToken::findOrFail($id);

        return view('admin.api-tokens.edit', compact('apitoken'));
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

        $requestData = $request->all();

        $apitoken = ApiToken::findOrFail($id);
        $apitoken->update($requestData);

        return redirect('admin/api-tokens')->with('flash_message', __('site.changes-saved'));
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
        ApiToken::destroy($id);

        return redirect('admin/api-tokens')->with('flash_message', __('site.record-deleted'));
    }
}
