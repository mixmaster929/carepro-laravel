<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\User;
use App\UserNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request,User $user)
    {
        $this->authorize('access','view_'.userType($user).'_notes');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $notes = $user->userNotes()->whereRaw("match(title,content) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $notes = $user->userNotes()->latest()->paginate($perPage);
        }

        if($user->role_id==2){
            $type = __('site.employers');
            $route = route('admin.employers.index');
        }
        else{
            $type = __('site.candidate');
            $route = route('admin.candidates.index');
        }

        return view('admin.notes.index', compact('notes','user','type','route','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(User $user)
    {
        $this->authorize('access','create_'.userType($user).'_note');
        return view('admin.notes.create',compact('user'));
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
        $this->authorize('access','create_'.userType($user).'_note');

        $this->validate($request,[
           'title'=>'required',
            'content'=>'required'
        ]);

        $requestData = $request->all();

        $requestData['content'] = saveInlineImages($requestData['content']);
        $requestData['author'] = Auth::user()->name;
        
        $user->userNotes()->create($requestData);

        return redirect()->route('admin.notes.index',['user'=>$user->id])->with('flash_message', __('site.changes-saved'));
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

        $note = UserNote::findOrFail($id);
        $this->authorize('access','view_'.userType($note->user).'_note');
        return view('admin.notes.show', compact('note'));
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

        $note = UserNote::findOrFail($id);
        $this->authorize('access','edit_'.userType($note->user).'_note');
        return view('admin.notes.edit', compact('note'));
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
            'title'=>'required',
            'content'=>'required'
        ]);
        $requestData = $request->all();
        $requestData['content'] = saveInlineImages($requestData['content']);

        $note = UserNote::findOrFail($id);
        $this->authorize('access','edit_'.userType($note->user).'_note');
        $note->update($requestData);

        return redirect()->route('admin.notes.index',['user'=>$note->user_id])->with('flash_message', __('site.changes-saved'));
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
        $userId = UserNote::find($id)->user_id;
        $this->authorize('access','delete_'.userType(UserNote::find($id)->user).'_note');
        UserNote::destroy($id);
        return redirect()->route('admin.notes.index',['user'=>$userId])->with('flash_message', __('site.record-deleted'));
    }
}
