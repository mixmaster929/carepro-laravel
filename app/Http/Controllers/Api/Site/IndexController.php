<?php

namespace App\Http\Controllers\Api\Site;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SettingResource;
use App\Setting;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function settings(){
            $settings = Setting::get();
            return SettingResource::collection($settings);
    }

    public function categories(){
        $categories = Category::orderBy('sort_order')->where('public',1)->get();
        return CategoryResource::collection($categories);
    }
}
