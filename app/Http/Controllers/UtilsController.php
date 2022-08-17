<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UtilsController extends Controller
{

    public function css(Request $request){

        $path = $request->path().'.css';
        //get current template
        $template = currentTemplate();

        $colors = $template->templateColors;

        if(!file_exists($path)){
            exit('Invalid file');
        }

        $content = file_get_contents($path);

        foreach($colors as $color){
            if(!empty($color->user_color)){
                $content = str_ireplace($color->original_color,$color->user_color,$content);
            }
        }

        $response = Response::make($content);
        $response->header('Content-Type', 'text/css');
        return $response;

    }
}
