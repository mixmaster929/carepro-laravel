<?php

namespace App\Http\Controllers\Api\Employer;

use App\Employer;
use App\EmployerFieldGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployerFieldGroupResource;
use App\Http\Resources\EmployerResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function profile(Request $request){
        $employer = $request->user()->employer;
        return new EmployerResource($employer);
    }

    public function update(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required|email|string|max:255',
        ];

        $user = $request->user();
        if($user->email != $request->email){
            $rules['email'] = 'required|email|string|max:255|unique:users';
        }
        foreach(EmployerFieldGroup::where('public',1)->orderBy('sort_order')->get() as $group) {
            foreach ($group->employerFields as $field) {

                if ($field->type == 'file') {
                    $required = '';
                    if ($field->required == 1) {
                        $required = 'required|';
                    }

                    $rules['field_' . $field->id] = 'nullable|' . $required . 'max:' . config('app.upload_size') . '|mimes:' . config('app.upload_files');
                } elseif ($field->required == 1 && ($field->type != 'checkbox' & $field->type !='radio')) {
                    $rules['field_' . $field->id] = 'required';
                }
            }

        }


        $this->validate($request,$rules);

        $requestData = $request->all();

        $user->update($requestData);


        //checkfor picture

        $employerRecord = Employer::where('user_id',$user->id)->first();
        $employerRecord->update($requestData);
        //$user->employer()->update($requestData);



        $customValues = [];
        //attach custom values
        foreach(EmployerFieldGroup::where('public',1)->orderBy('sort_order')->get() as $group) {
            foreach ($group->employerFields as $field) {

                if ($field->type == 'file') {
                    if ($request->hasFile('field_' . $field->id)) {
                        //get current file name
                        if ($user->employerFields()->where('id', $field->id)->first()) {
                            $fileName = $user->employerFields()->where('id', $field->id)->first()->pivot->value;
                            @unlink($fileName);
                        }

                        //generate name for file

                        $name = $_FILES['field_' . $field->id]['name'];

                        //dd($name);


                        $extension = $request->{'field_' . $field->id}->extension();
                        //  dd($extension);

                        $name = str_ireplace('.' . $extension, '', $name);

                        $name = $user->id . '_' . time() . '_' . safeUrl($name) . '.' . $extension;

                        $path = $request->file('field_' . $field->id)->storeAs(EMPLOYER_FILES, $name, 'public_uploads');


                        $file = UPLOAD_PATH . '/' . $path;
                        $customValues[$field->id] = ['value' => $file];
                    } elseif ($user->employerFields()->where('id', $field->id)->first()) {
                        $fileName = $user->employerFields()->where('id', $field->id)->first()->pivot->value;
                        $customValues[$field->id] = ['value' => $fileName];
                    }
                } else {
                    $customValues[$field->id] = ['value' => isset($requestData['field_' . $field->id])?$requestData['field_' . $field->id]:''];
                }


            }
        }

        $user->employerFields()->sync($customValues);

        return response()->json([
            'status'=>true,
            'message'=>__('site.changes-saved')
        ]);

    }

    public function employerFields(){

        $groups = EmployerFieldGroup::where('public',1)->orderBy('sort_order')->get();
        return  EmployerFieldGroupResource::collection($groups);
    }

}
