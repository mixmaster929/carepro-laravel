<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_articles');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $articles = Article::whereRaw("match(title,content,slug) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $articles = Article::latest()->paginate($perPage);
        }

        return view('admin.articles.index', compact('articles','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_article');
        return view('admin.articles.create');
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
        $this->authorize('access','create_article');
        $this->validate($request,[
            'title'=>'required'
        ]);

        $requestData = $request->all();

        if(empty($request->slug)){

            $slug = safeUrl($request->title);
            $count = 1;
            while(Article::where('slug',$slug)->count()>0){
                $slug = safeUrl($request->title).'-'.$count;
                $count++;
            }
            $requestData['slug'] = $slug;
        }else{
            $slug = safeUrl($request->slug);
            $count = 1;
            while(Article::where('slug',$slug)->count()>0){
                $slug = safeUrl($request->slug).'-'.$count;
                $count++;
            }
            $requestData['slug'] = $slug;
        }

        $requestData['content'] = saveInlineImages($requestData['content']);


        
        $article = Article::create($requestData);
 

        return redirect('admin/articles')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_article');
        $article = Article::findOrFail($id);

        return view('admin.articles.show', compact('article'));
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
        $this->authorize('access','edit_article');
        $article = Article::findOrFail($id);

        return view('admin.articles.edit', compact('article'));
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
        $this->authorize('access','edit_article');
        $this->validate($request,[
            'title'=>'required'
        ]);

        $requestData = $request->all();

        if(empty($request->slug)){

            $slug = safeUrl($request->title);
            $count = 1;
            while(Article::where('slug',$slug)->where('id','!=',$id)->count()>0){
                $slug = safeUrl($request->title).'-'.$count;
                $count++;
            }
            $requestData['slug'] = $slug;
        }else{
            $slug = safeUrl($request->slug);
            $count = 1;
            while(Article::where('slug',$slug)->where('id','!=',$id)->count()>0){
                $slug = safeUrl($request->slug).'-'.$count;
                $count++;
            }
            $requestData['slug'] = $slug;
        }
        $requestData['content'] = saveInlineImages($requestData['content']);

        
        $article = Article::findOrFail($id);
        $article->update($requestData);
 

        return redirect('admin/articles')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_article');
        Article::destroy($id);

        return redirect('admin/articles')->with('flash_message', __('site.record-deleted'));
    }
}
