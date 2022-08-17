<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplatesController extends Controller
{
    use HelperTrait;

    public function index(){
        //get list of all templates in templates folder
        $templates = getDirectoryContents(TEMPLATE_PATH);

        //get currently installed template
        $currentTemplate = Template::where('enabled',1)->first();


        return view('admin.templates.index',compact('templates','currentTemplate'));
    }

    public function install($templateDir){

        //first check if this template exists yet
        $template = Template::where('directory',$templateDir)->first();
        if(!$template){
            Template::where('directory','!=',$template)->update(['enabled'=>0]);
            $info = templateInfo($templateDir);

            $template = Template::create([
               'name'=>$info['name'],
                'enabled'=>1,
                'directory'=>$templateDir
            ]);

        }
        else{
            Template::where('directory','!=',$template)->update(['enabled'=>0]);
            $template->enabled = 1;
            $template->save();
        }

        return back()->with('flash_message',__('site.template-installed'));

    }

    public function settings(){
        //get current template
        $template = $this->getCurrentTemplate();
        if(!$template){
            return back();
        }

        $optionPath = TEMPLATE_PATH.'/'.$template->directory.'/options';

        $options = getDirectoryContents($optionPath);

        //reorder options
        $optionSort= [];
        foreach($options as $option){
            $info= include $optionPath.'/'.$option.'/info.php';
            $optionSort[$info['position']] = $option;
        }

        ksort($optionSort);


        $settings = [];
        foreach($optionSort as $option){
            $data = [];
            $info= include $optionPath.'/'.$option.'/info.php';
            $data['name'] = __("temp_{$template->directory}.".$info['name']);
            $data['description'] = __("temp_{$template->directory}.".$info['description']);
            $data['form'] = $template->directory.'.options.'.$option.'.form';

            $optionRow = $template->templateOptions()->where('name',$option)->first();
            if($optionRow){
                $data['values'] = sunserialize($optionRow->value);
                $data['enabled'] = $optionRow->enabled;
            }
            else{
                $data['values'] = [];
                $data['enabled'] = 0;
            }

            $settings[$option] = $data;
        }



        return view('admin.templates.settings',compact('settings','template'));
    }

    public function saveOptions(Request $request,$option){
        $template = $this->getCurrentTemplate();

        $data = $request->all();
        unset($data['csrf-token']);
        //check if template has option
        $optionRow = $template->templateOptions()->where('name',$option)->first();
        if(!$optionRow){
            $template->templateOptions()->create([
               'name'=>$option,
                'value'=>serialize($data),
                'enabled'=>$request->enabled
            ]);
        }
        else{
            $template->templateOptions()->where('name',$option)->update([
                'value'=>serialize($data),
                'enabled'=>$request->enabled
            ]);
        }

        return response()->json(['status'=>true]);

    }

    public function upload(Request $request){

        $validator = Validator::make($request->all(),
            [
                'image'=>'required|file|max:'.config('app.upload_size').'|mimes:jpeg,png,gif'
            ]
            );
        if($validator->fails()){
            return response()->json([
               'error'=> implode(' , ',$validator->messages()->all()),
                'status'=>false
            ]);
        }

        $requestData = $request->all();

        if($request->hasFile('image')){
            //generate name for file

            $name = $_FILES['image']['name'];


            $path =  $request->file('image')->store(TEMPLATE_FILES,'public_uploads');



            $file = UPLOAD_PATH.'/'.$path;
            $requestData['file_path'] = $file;
            $requestData['file_name'] = $name;

            return response()->json([
                'file_path'=> asset($file),
                'file_name'=>$file,
                'status'=>true
            ]);
        }


    }

    public function colors(){
        $template = $this->getCurrentTemplate();
        if(!$template){
            return back();
        }

        //get the color list for this template
        $colorFile = TEMPLATE_PATH.'/'.$template->directory.'/colors.php';
        if(!file_exists($colorFile)){
            return back()->with('flash_message',__('site.unavailable'));
        }

        $colorList = include $colorFile;

        return view('admin.templates.colors',compact('colorList','template'));

    }

    public function saveColors(Request $request){
        $template = $this->getCurrentTemplate();
        if(!$template){
            return back();
        }

        //get the color list for this template
        $colorFile = TEMPLATE_PATH.'/'.$template->directory.'/colors.php';
        if(!file_exists($colorFile)){
            return back()->with('flash_message',__('site.unavailable'));
        }

        $colorList = include $colorFile;

        $requestData = $request->all();

        foreach($colorList as $color){

            //check for color
            $templateColor = $template->templateColors()->where('original_color',$color)->first();

            if(!$templateColor){
                $templateColor = $template->templateColors()->create([
                   'original_color'=>$color
                ]);
            }

            $templateColor->user_color = $requestData[$color.'_new'];
            $templateColor->save();
        }

        return back()->with('flash_message',__('site.changes-saved'));
    }


    private function getCurrentTemplate(){
        return Template::where('enabled',1)->first();
    }
}
