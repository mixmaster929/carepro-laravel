<?php

namespace App\Http\Controllers\Site;

use App\BlogCategory;
use App\BlogPost;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BlogController extends Controller
{

    public function index(Request $request){

        $title = __('site.blog');
        $posts = BlogPost::whereDate('publish_date','<=',Carbon::now()->toDateTimeString())->where('status',1)->orderBy('publish_date','desc');
        $recent = BlogPost::whereDate('publish_date','<=',Carbon::now()->toDateTimeString())->where('status',1)->orderBy('publish_date','desc')->limit(5)->get();

        $categories = BlogCategory::orderBy('sort_order')->get();

        $category = $request->get('category');
        if(!empty($category) && BlogCategory::find($category)){
            $title = BlogCategory::find($category)->name;
            $posts = $posts->whereHas('blogCategories',function($q) use($category){
                $q->where('id',$category);
            });

        }

        if(!empty($request->q)){
            $keyword = $request->q;
            $posts = $posts->whereRaw("match(title,content,meta_title,meta_description) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            $title = __('site.search-results').': '.$request->q;

        }

        $posts = $posts->paginate(20);

        return tview('site.blog.index',compact('posts','recent','title','categories'));

    }

    public function post(BlogPost $blogPost){
        $categories = BlogCategory::orderBy('sort_order')->get();
        return tview('site.blog.post',compact('blogPost','categories'));
    }

}
