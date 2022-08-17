<?php

namespace App\Http\Controllers\Employer;

use App\Employer;
use App\EmployerField;
use App\EmployerFieldGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function profile(){
        $employer = Auth::user();
        return view('employer.profile.profile', compact('employer'));
    }

    public function update(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required|email|string|max:255',
        ];

        $user = Auth::user();
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
                } elseif ($field->required == 1) {
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


        //now save custom fields
        $fields = EmployerField::get();

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
                    $customValues[$field->id] = ['value' => $requestData['field_' . $field->id]];
                }


            }
        }

        $user->employerFields()->sync($customValues);

        return back()->with('flash_message',__('site.changes-saved'));

    }


}
