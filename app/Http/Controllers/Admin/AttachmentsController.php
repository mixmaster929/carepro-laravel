<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\User;
use App\UserAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request,User $user)
    {
        $this->authorize('access','view_'.userType($user).'_attachments');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $attachments = $user->userAttachments()->whereRaw("match(name) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $attachments = $user->userAttachments()->latest()->paginate($perPage);
        }

        if($user->role_id==2){
            $type = __('site.employers');
            $route = route('admin.employers.index');
        }
        else{
            $type = __('site.candidate');
            $route = route('admin.candidates.index');
        }

        return view('admin.attachments.index', compact('attachments','user','type','route','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(User $user)
    {
        $this->authorize('access','create_'.userType($user).'_attachment');
        return view('admin.attachments.create',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,User $user)
    {
        $this->authorize('access','create_'.userType($user).'_attachment');
        $this->validate($request,[
            'name'=>'required',
            'file'=>'required|file|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files')
        ]);
        $requestData = $request->all();
        $requestData['author'] = Auth::user()->name;

        //store file
        if($request->hasFile('file')){
            //generate name for file

            $name = $_FILES['file']['name'];


            $extension = $request->{'file'}->extension();

            $name = str_ireplace('.'.$extension,'',$name);

            $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

            $path =  $request->file('file')->storeAs(USER_FILES,$name,'public_uploads');



            $file = UPLOAD_PATH.'/'.$path;
            $requestData['path'] = $file;
        }

        $user->userAttachments()->create($requestData);

        return redirect()->route('admin.attachments.index',['user'=>$user->id])->with('flash_message', __('site.changes-saved'));
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
        $attachment = UserAttachment::findOrFail($id);
        $this->authorize('access','view_'.userType($attachment->user).'_attachment');
        return view('admin.attachments.show', compact('attachment'));
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
        $attachment = UserAttachment::findOrFail($id);
        $this->authorize('access','edit_'.userType($attachment->user).'_attachment');
        return view('admin.attachments.edit', compact('attachment'));
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
            'file'=>'file|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files')
        ]);
        $requestData = $request->all();
        $this->validate($request,[
            'name'=>'required'
        ]);

        $attachment = UserAttachment::findOrFail($id);
        $this->authorize('access','edit_'.userType($attachment->user).'_attachment');

        //store file
        if($request->hasFile('file')){
            //generate name for file
            @unlink($attachment->path);
            $name = $_FILES['file']['name'];


            $extension = $request->{'file'}->extension();

            $name = str_ireplace('.'.$extension,'',$name);

            $name = $attachment->user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

            $path =  $request->file('file')->storeAs(USER_FILES,$name,'public_uploads');



            $file = UPLOAD_PATH.'/'.$path;
            $requestData['path'] = $file;
        }


        $attachment->update($requestData);

        return redirect()->route('admin.attachments.index',['user'=>$attachment->user_id])->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_'.userType(UserAttachment::find($id)->user).'_attachment');
        $userId = UserAttachment::find($id)->user_id;
        @unlink(UserAttachment::find($id)->path);

        UserAttachment::destroy($id);
        return redirect()->route('admin.attachments.index',['user'=>$userId])->with('flash_message', __('site.record-deleted'));
    }
}
