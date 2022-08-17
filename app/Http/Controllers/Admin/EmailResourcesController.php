<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\EmailResource;
use Illuminate\Http\Request;

class EmailResourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_email_resources');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $emailresources = EmailResource::latest()->whereRaw("match(name,file_name) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $emailresources = EmailResource::latest()->paginate($perPage);
        }

        return view('admin.email-resources.index', compact('emailresources','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_email_resource');
        return view('admin.email-resources.create');
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
        $this->authorize('access','create_email_resource');
        $this->validate($request,[
            'name'=>'required',
            'file'=>'required|file|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files')
        ]);

        $requestData = $request->all();

        if($request->hasFile('file')){
            //generate name for file

            $name = $_FILES['file']['name'];


        //    $extension = $request->{'file'}->extension();

         //   $name = str_ireplace('.'.$extension,'',$name);

           // $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

            $path =  $request->file('file')->store(EMAIL_FILES,'public_uploads');



            $file = UPLOAD_PATH.'/'.$path;
            $requestData['file_path'] = $file;
            $requestData['file_name'] = $name;
        }


        EmailResource::create($requestData);

        return redirect('admin/email-resources')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_email_resource');
        $emailresource = EmailResource::findOrFail($id);

        return response()->download($emailresource->file_path,$emailresource->file_name);
        //return view('admin.email-resources.show', compact('emailresource'));
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
        $this->authorize('access','edit_email_resource');
        $emailresource = EmailResource::findOrFail($id);

        return view('admin.email-resources.edit', compact('emailresource'));
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
        $this->authorize('access','edit_email_resource');
        $this->validate($request,[
            'name'=>'required',
            'file'=>'file|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files')
        ]);
        $requestData = $request->all();

        $emailresource = EmailResource::findOrFail($id);

        if($request->hasFile('file')){
            //generate name for file
            @unlink($emailresource->file_path);
            $name = $_FILES['file']['name'];


       /*     $extension = $request->{'file'}->extension();

            $name = str_ireplace('.'.$extension,'',$name);

            $name = $attachment->user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;*/

            $path =  $request->file('file')->store(EMAIL_FILES,'public_uploads');



            $file = UPLOAD_PATH.'/'.$path;
            $requestData['file_path'] = $file;
            $requestData['file_name'] = $name;
        }


        $emailresource->update($requestData);

        return redirect('admin/email-resources')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_email_resource');
        $emailresource = EmailResource::findOrFail($id);
        @unlink($emailresource->file_path);

        EmailResource::destroy($id);
        return redirect('admin/email-resources')->with('flash_message', __('site.record-deleted'));
    }
}
