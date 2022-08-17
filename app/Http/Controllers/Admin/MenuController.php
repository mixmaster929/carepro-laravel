<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Category;
use App\FooterMenu;
use App\HeaderMenu;
use App\Http\Controllers\Controller;
use App\JobCategory;
use App\OrderForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{

    public function headerMenu(){
        return view('admin.menu.header_menu',$this->getLinks());
    }

    public function loadHeaderMenu(){
        $menus = HeaderMenu::where('parent_id',0)->orderBy('sort_order')->get();

        return view('admin.menu.load_header',compact('menus'));
    }

    public function saveHeaderMenu(Request $request){

        $validator = Validator::make($request->all(),
            [
                'name'=>'required',
                'label'=>'required',
                'url'=>'required',
                'type'=>'required',

            ]
        );
        if($validator->fails()){
            return response()->json([
                'error'=> implode(' , ',$validator->messages()->all()),
                'status'=>false
            ]);
        }


        $requestData = $request->all();
        if(empty($request->parent_id)){
            $requestData['parent_id'] =0;
        }

        if(empty($request->name)){
            $requestData['name'] = $request->label;
        }

        if(empty($request->sort_order)){
            $requestData['sort_order']=0;
        }

        $headerMenu = HeaderMenu::create($requestData);
        return response()->json([
           'status'=>true
        ]);

    }

    public function updateHeaderMenu(Request $request,HeaderMenu $headerMenu){
        $validator = Validator::make($request->all(),
            [
                'label'=>'required',
                'sort_order'=>'required',
            ]
        );
        if($validator->fails()){
            return response()->json([
                'error'=> implode(' , ',$validator->messages()->all()),
                'status'=>false
            ]);
        }

        $requestData = $request->all();
        $headerMenu->update($requestData);
        return response()->json([
            'status'=>true
        ]);
    }

    public function deleteHeaderMenu(HeaderMenu $headerMenu){

        $headerMenu->delete();
        return response()->json(['status'=>true]);
    }


    public function footerMenu(){


        return view('admin.menu.footer_menu',$this->getLinks());
    }

    public function loadFooterMenu(){
        $menus = FooterMenu::where('parent_id',0)->orderBy('sort_order')->get();

        return view('admin.menu.load_footer',compact('menus'));
    }

    public function saveFooterMenu(Request $request){

        $validator = Validator::make($request->all(),
            [
                'name'=>'required',
                'label'=>'required',
                'url'=>'required',
                'type'=>'required',

            ]
        );
        if($validator->fails()){
            return response()->json([
                'error'=> implode(' , ',$validator->messages()->all()),
                'status'=>false
            ]);
        }


        $requestData = $request->all();
        if(empty($request->parent_id)){
            $requestData['parent_id'] =0;
        }

        if(empty($request->name)){
            $requestData['name'] = $request->label;
        }

        if(empty($request->sort_order)){
            $requestData['sort_order']=0;
        }

        $footerMenu = FooterMenu::create($requestData);
        return response()->json([
            'status'=>true
        ]);

    }

    public function updateFooterMenu(Request $request,FooterMenu $footerMenu){
        $validator = Validator::make($request->all(),
            [
                'label'=>'required',
                'sort_order'=>'required',
            ]
        );
        if($validator->fails()){
            return response()->json([
                'error'=> implode(' , ',$validator->messages()->all()),
                'status'=>false
            ]);
        }

        $requestData = $request->all();
        $footerMenu->update($requestData);
        return response()->json([
            'status'=>true
        ]);
    }

    public function deleteFooterMenu(FooterMenu $footerMenu){

        $footerMenu->delete();
        return response()->json(['status'=>true]);
    }

    private function getLinks(){
        $pages = [
            [
                'name'=>__('site.home-page'),
                'url'=> route('homepage', [], false)
            ],
            [
                'name'=> __('site.profiles'),
                'url'=> route('profiles', [], false)
            ],
            [
                'name'=> __('site.vacancies'),
                'url'=> route('vacancies', [], false)
            ],
            [
                'name'=>__('site.blog'),
                'url'=> route('blog', [], false)
            ],
            [
                'name'=> __('site.contact'),
                'url'=> route('contact', [], false)
            ]

        ];

        $articles = [];

        foreach(Article::orderBy('title')->get() as $article){

            $articles[] = [
                'name'=> limitLength($article->title,150),
                'url'=> route('article',['slug'=>$article->slug],false)
            ];

        }

        $categories = [];

        foreach(Category::orderBy('sort_order')->get() as $category){

            $categories[] = [
                'name'=>limitLength($category->name,150),
                'url'=> route('profiles', [], false).'?category='.$category->id
            ];

        }

        $jobCategories = [];
        foreach(JobCategory::orderBy('sort_order')->get() as $jobCategory)
        {
            $jobCategories[] = [
                'name'=>limitLength($jobCategory->name,150),
                'url'=> route('vacancies', [], false).'?category='.$jobCategory->id
            ];
        }

        $orderForms = [];
        foreach(OrderForm::get() as $orderForm){
            $orderForms[] = [
              'name'=>  limitLength($orderForm->name,150),
                'url'=> route('order-form', ['orderForm'=>$orderForm->id], false)
            ];
        }

        return compact('pages','articles','categories','jobCategories','orderForms');
    }

}
