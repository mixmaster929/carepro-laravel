<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class BlogPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_blogs');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $blogposts = BlogPost::whereRaw("match(title,content,meta_title,meta_description) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $blogposts = BlogPost::latest()->paginate($perPage);
        }

        return view('admin.blog-posts.index', compact('blogposts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_blog');
        return view('admin.blog-posts.create');
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
        $this->authorize('access','create_blog');
        $this->validate($request,[
            'title'=>'required',
            'content'=>'required',
            'cover_photo' => 'file|max:'.config('app.upload_size').'|mimes:jpeg,png,gif',
        ]);
        $requestData = $request->all();

        $requestData['user_id'] = Auth::user()->id;

        if($request->hasFile('cover_photo')) {
            $path =  $request->file('cover_photo')->store(BLOG_FILES,'public_uploads');

            $file = UPLOAD_PATH.'/'.$path;
            $img = Image::make($file);

            $img->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($file);

            $requestData['cover_photo'] = $file;
        }
        else{
            $requestData['cover_photo'] =null;
        }

        $requestData['content'] = saveInlineImages($requestData['content']);

        if(empty($request->publish_date)){
            $requestData['publish_date'] = Carbon::now()->toDateString();
        }

        $blogPost= BlogPost::create($requestData);

        $blogPost->blogCategories()->sync($request->categories);

        return redirect('admin/blog-posts')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_blog');
        $blogpost = BlogPost::findOrFail($id);

        return view('admin.blog-posts.show', compact('blogpost'));
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
        $this->authorize('access','edit_blog');
        $blogpost = BlogPost::findOrFail($id);

        return view('admin.blog-posts.edit', compact('blogpost'));
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
        $this->authorize('access','edit_blog');
        $this->validate($request,[
            'title'=>'required',
            'content'=>'required',
            'cover_photo' => 'file|max:'.config('app.upload_size').'|mimes:jpeg,png,gif',
        ]);
        $requestData = $request->all();

        $blogpost = BlogPost::findOrFail($id);


        if($request->hasFile('cover_photo')){
            @unlink($blogpost->cover_photo);

            $path =  $request->file('cover_photo')->store(BLOG_FILES,'public_uploads');

            $file = UPLOAD_PATH.'/'.$path;
            $img = Image::make($file);

            $img->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($file);

            $requestData['cover_photo'] = $file;
        }

        $requestData['content'] = saveInlineImages($requestData['content']);

        if(empty($request->publish_date)){
            $requestData['publish_date'] = Carbon::now()->toDateString();
        }

        $blogpost->update($requestData);

        $blogpost->blogCategories()->sync($request->categories);

        return redirect('admin/blog-posts')->with('flash_message', __('site.changes-saved'));
    }

    public function removePicture($id){
        $this->authorize('access','edit_blog');
        $dept = BlogPost::find($id);
        @unlink($dept->cover_photo);
        $dept->cover_photo = null;
        $dept->save();
        return back()->with('flash_message',__('site.picture').' '.__('site.deleted'));
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
        $this->authorize('access','delete_blog');
        BlogPost::destroy($id);

        return redirect('admin/blog-posts')->with('flash_message', __('site.record-deleted'));
    }
}
