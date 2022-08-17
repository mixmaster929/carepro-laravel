<?php

namespace App\Http\Controllers\Admin;

use App\Employer;
use App\EmployerField;
use App\EmployerFieldGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Lib\HelperTrait;
use App\Lib\ProfileGenerator;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ParseCsv\Csv;

class EmployersController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_employers');
        $keyword = $request->get('search');
        $perPage = 25;

        $params= $request->all();

        //default sorting
        $sort = 'n';

        if(isset($request->sort)){
            $sort = $request->sort;
            session(['sort' => $sort]);
        }
        elseif(session('sort')){
            $sort = session('sort');
        }



        $request->sort = $sort;

        $employers = $this->getEmployers($request);
        $total = $this->getEmployers($request)->count();

        $employers = $employers->paginate($perPage);

        unset($params['search'],$params['page'],$params['field_id'],$params['sort']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;
        }




        return view('admin.employers.index', compact('employers','filterParams','perPage','total','sort'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_employer');
        return view('admin.employers.create');
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
        $this->authorize('access','create_employer');

        $rules = [
            'name'=>'required',
            'email'=>'required|email|string|max:255|unique:users',
            'status'=>'required',
        ];

        foreach(EmployerField::get() as $field){

            if($field->type=='file'){
                $required = '';
                if($field->required==1){
                    $required = 'required|';
                }

                $rules['field_'.$field->id] = 'nullable|'.$required.'max:'.config('app.upload_size').'|mimes:'.config('app.upload_files');
            }
            elseif($field->required==1){
                $rules['field_'.$field->id] = 'required';
            }
        }




        $this->validate($request,$rules);
        $requestData = $request->all();
        //create password
        $password= $request->password;
        if(empty($password)){
            $password = Str::random(8);
        }

        $requestData['password'] = Hash::make($password);
        $requestData['role_id'] = 2;

        //First create user
        $user= User::create($requestData);



        $user->employer()->create($requestData);

        //now save custom fields
        $fields = EmployerField::get();

        $customValues = [];
        //attach custom values
        foreach($fields as $field){
            if(isset($requestData['field_'.$field->id]))
            {

                if($field->type=='file'){
                    if($request->hasFile('field_'.$field->id)){
                        //generate name for file

                        $name = $_FILES['field_'.$field->id]['name'];

                        //dd($name);


                        $extension = $request->{'field_'.$field->id}->extension();
                        //  dd($extension);

                        $name = str_ireplace('.'.$extension,'',$name);

                        $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                        $path =  $request->file('field_'.$field->id)->storeAs(EMPLOYER_FILES,$name,'public_uploads');



                        $file = UPLOAD_PATH.'/'.$path;
                        $customValues[$field->id] = ['value'=>$file];
                    }
                }
                else{
                    $customValues[$field->id] = ['value'=>$requestData['field_'.$field->id]];
                }



            }


        }

        $user->employerFields()->sync($customValues);

        //notify
        if($request->notify==1){
            $message = __('mails.new-account',[
                'siteName'=>setting('general_site_name'),
                'email'=>$requestData['email'],
                'password'=>$password,
                'link'=> url('/login')
            ]);
            $subject = __('mails.new-account-subj',[
                'siteName'=>setting('general_site_name')
            ]);
            $this->sendEmail($requestData['email'],$subject,$message);
        }



        return redirect('admin/employers')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_employer');
        $employer = User::findOrFail($id);

        return view('admin.employers.show', compact('employer'));
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
        $this->authorize('access','edit_employer');
        $employer = User::findOrFail($id);

        return view('admin.employers.edit', compact('employer'));
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

        $this->authorize('access','edit_employer');

        $rules = [
            'name'=>'required',
            'email'=>'required|email|string|max:255',
            'status'=>'required',
        ];

        $user = User::find($id);
        if($user->email != $request->email){
            $rules['email'] = 'required|email|string|max:255|unique:users';
        }

        foreach(EmployerField::get() as $field){

            if($field->type=='file'){
                $required = '';
                if($field->required==1){
                    $required = 'required|';
                }

                $rules['field_'.$field->id] = 'nullable|'.$required.'max:'.config('app.upload_size').'|mimes:'.config('app.upload_files');
            }
            elseif($field->required==1){
                $rules['field_'.$field->id] = 'required';
            }
        }




        $this->validate($request,$rules);

        $requestData = $request->all();

        $password= $request->password;
        if(!empty($password)){
            $requestData['password'] = Hash::make($password);
        }
        else{
            unset($requestData['password']);
        }

        $user = User::findOrFail($id);
        $user->update($requestData);


        //checkfor picture

        $employerRecord = Employer::where('user_id',$user->id)->first();
        $employerRecord->update($requestData);
        //$user->employer()->update($requestData);


        //now save custom fields
        $fields = EmployerField::get();

        $customValues = [];
        //attach custom values
        foreach($fields as $field) {
            if (isset($requestData['field_' . $field->id]) || $field->type=='file'){
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




        return redirect('admin/employers')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_employer');

        //Clean up all files for employer


        User::destroy($id);

        return redirect('admin/employers')->with('flash_message', __('site.record-deleted'));
    }




    public function removeFile($fieldId,$userId){
        $user = User::find($userId);
        $file = $user->employerFields()->where('id',$fieldId)->first()->pivot->value;
        @unlink($file);
        $user->employerFields()->detach($fieldId);
        return back()->with('flash_message',__('site.file').' '.__('site.deleted'));
    }


    public function search(Request $request){

        $keyword = $request->get('term');

        if(empty($keyword)){
            return response()->json([]);
        }

       // $employers = User::where('role_id',2)->has('employer')->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->limit(1000)->get();
        $employers = User::where('role_id',2)->has('employer')->where(function($query) use($keyword){
            $query->where('name','LIKE','%'.$keyword.'%')->orWhere('email','LIKE','%'.$keyword.'%')->orWhere('telephone','LIKE','%'.$keyword.'%');
        })->limit(1000)->get();

     //   ->where('value','LIKE','%'.$params['custom_field'].'%')
        $formattedUsers = [];

        foreach($employers as $employer){
            if($request->get('format')=='number'){
                $formattedUsers[] = ['id'=>$employer->id,'text'=>"{$employer->name} ({$employer->telephone})"];
            }
            else{
                $formattedUsers[] = ['id'=>$employer->id,'text'=>"{$employer->name} <{$employer->email}>"];
            }

        }


        // $formattedUsers['pagination']=['more'=>false];
        return response()->json($formattedUsers);
    }

    public function importForm(){
        $this->authorize('access','create_employer');
        return view('admin.employers.import1',['active'=>1]);
    }

    public function importUpload(Request $request){
        $this->authorize('access','create_employer');
        $this->validate($request,[
            'file'=>'required|file|max:'.config('app.upload_size').'|mimes:csv,txt'
        ]);

        $name= safeFile($_FILES['file']['name']);
        $tmpName = $_FILES['file']['tmp_name'];



        //create temp dir
        $path = '../storage/tmp/csv';
        if(!is_dir($path)){
            rmkdir($path);
        }



        $newName = $path.'/'.$name;
        //movefile
        if(!rename($tmpName,$newName)){
            return back()->with('flash_message',__('site.import-failed'));
        }

        //save name of file
        session(['e-import-csv'=>$newName]);
        return redirect()->route('admin.employers.import-2');

    }

    public function importFields(){
        $this->authorize('access','create_employer');
        $file = session()->get('e-import-csv');
        if(empty($file) || !file_exists($file)){
            return redirect()->route('admin.employers.import-1')->with('flash_message',__('site.upload-csv'));
        }

        $csv = new Csv();
        $csv->delimiter= ',';
        $csv->parse($file);

        $data = $csv->data;


        $options = [''=>''];
        foreach ($data[0] as $key=>$value)
        {
            $options[$key] = $key;
        }

        //now get all fields


        $allFields = $this->getImportFields();
        $columns = $allFields['columns'];
        $required = $allFields['required'];


        return view('admin.employers.import2',['active'=>2,'columns'=>$columns,'options'=>$options,'required'=>$required]);
    }

    public function saveImportFields(Request $request){
        $this->authorize('access','create_employer');

        $rules = [
            'name'=>'required',
            'email'=>'required',
        ];

        foreach(EmployerField::where('type','!=','file')->get() as $field){

            if($field->required==1){
                $rules['field_'.$field->id] = 'required';
            }
        }

        $data = $request->all();
        unset($data['_token']);
        $data = serialize($data);
        session()->put('e-import-fields',$data);
        return redirect()->route('admin.employers.import-preview');

    }

    public function importPreview(){
        $this->authorize('access','create_employer');
        $file = session()->get('e-import-csv');
        if(empty($file) || !file_exists($file)){
            return redirect()->route('admin.employers.import-1')->with('flash_message',__('site.upload-csv'));
        }

        $fields = session()->get('e-import-fields');
        if(empty($fields)){
            return redirect()->route('admin.employers.import-2')->with('flash_message',__('site.set-fields'));
        }

        $fields = unserialize($fields);


        $csv = new Csv();
        $csv->delimiter= ',';
        $csv->parse($file);

        $data = $csv->data;

        //now get the listed fields
        $allFields = $this->getImportFields();
        $columns = $allFields['columns'];
        $required = $allFields['required'];

        $values = $data[0];

        return view('admin.employers.import3',['active'=>3,'columns'=>$columns,'values'=>$values,'fields'=>$fields]);
    }

    private function getImportFields(){

        $columns = [
            'name'=>__('site.name'),
            'email'=>__('site.email'),
            'telephone'=>__('site.telephone')
        ];

        $required = ['name'=>'name','email'=>'email'];


        foreach(EmployerFieldGroup::orderBy('sort_order')->get() as $group){
            foreach($group->employerFields()->where('type','!=','file')->orderBy('sort_order')->get() as $field){
                $columns['field_'.$field->id] = $field->name;
                if($field->required==1){
                    $required['field_'.$field->id] = 'field_'.$field->id;
                }
            }

        }

        return [
            'columns'=>$columns,
            'required'=>$required
        ];
    }

    public function processImport(Request $request){
        set_time_limit(86400);
        $this->authorize('access','create_employer');
        $file = session()->get('e-import-csv');
        if(empty($file) || !file_exists($file)){
            return redirect()->route('admin.employers.import-1')->with('flash_message',__('site.upload-csv'));
        }

        $fields = session()->get('e-import-fields');
        if(empty($fields)){
            return redirect()->route('admin.employers.import-2')->with('flash_message',__('site.set-fields'));
        }

        $fields = unserialize($fields);


        $csv = new Csv();
        $csv->delimiter= ',';
        $csv->parse($file);

        $data = $csv->data;

        //now get the listed fields
        /*  $allFields = $this->getImportFields();
          $columns = $allFields['columns'];
          $required = $allFields['required'];*/

        $totalImport = 0;
        $totalUpdate = 0;

        foreach($data as $requestData){

            $employerData=[];
            foreach($fields as $key=>$value){
                $employerData[$key] = @$requestData[$value];
            }

            if(empty($employerData['name']) || empty($employerData['email'])){
                continue;
            }



            if(User::where('email',$employerData['email'])->exists()){
                if($request->update==1){
                    $user = User::where('email',$employerData['email'])->first();

                    $user->update($employerData);
                    //$user->employer()->update($employerData);

                    $employerRecord = Employer::where('user_id',$user->id)->first();
                    $employerRecord->update($employerData);

                    $cfields = EmployerField::where('type','!=','file')->get();
                    $customValues = [];
                    foreach($cfields as $field){
                        $customValues[$field->id] = ['value'=>$employerData['field_'.$field->id]];
                    }
                    $user->employerFields()->sync($customValues);

                    $totalUpdate++;


                }

            }
            else{
                $password = Str::random(8);
                $employerData['password'] = Hash::make($password);
                $employerData['role_id']= 2;
                $user= User::create($employerData);
                $user->employer()->create($employerData);


                $cfields = EmployerField::where('type','!=','file')->get();
                $customValues = [];
                foreach($cfields as $field){
                    $customValues[$field->id] = ['value'=>$employerData['field_'.$field->id]];
                }
                $user->employerFields()->sync($customValues);
                $totalImport++;

                //notify
                if($request->notify==1){
                    $message = __('mails.new-account',[
                        'siteName'=>setting('general_site_name'),
                        'email'=>$employerData['email'],
                        'password'=>$password,
                        'link'=> url('/login')
                    ]);
                    $subject = __('mails.new-account-subj',[
                        'siteName'=>setting('general_site_name')
                    ]);
                    $this->sendEmail($requestData['email'],$subject,$message);
                }
            }


        }


        if(!empty($totalImport)){
            $this->successMessage(__('site.import-count',['total'=>$totalImport]));
        }

        if(!empty($totalUpdate)){
            $this->successMessage(__('site.update-count',['total'=>$totalUpdate]));
        }

        if(empty($totalImport) && empty($totalUpdate)){
            $this->errorMessage(__('site.no-import-update'));
        }
        return redirect()->route('admin.employers.import-complete');


    }

    public function completeImport(){
        $this->authorize('access','create_employer');
        session()->forget(['e-import-csv','e-import-fields']);
        return view('admin.employers.import4',['active'=>4]);

    }


    public function export(Request $request){
        $this->authorize('access','view_employers');

        $employers = $this->getEmployers($request);

        $file = "export.txt";
        if (file_exists($file)) {
            unlink($file);
        }



        $myfile = fopen($file, "w") or die("Unable to open file!");

        $columns = array('#',__('site.name'),__('site.email'),__('site.telephone'));

        $fields = EmployerField::orderBy('sort_order')->get();


        foreach(EmployerFieldGroup::orderBy('sort_order')->get() as $group){
            foreach($group->employerFields()->orderBy('sort_order')->get() as $field){
                $columns[] = $field->name;
            }

        }



        fputcsv($myfile,$columns );

        foreach($employers->cursor() as $employer){
            $csvData = [$employer->id,$employer->name,$employer->email,$employer->telephone];


            foreach(EmployerFieldGroup::orderBy('sort_order')->get() as $group){
                foreach($group->employerFields()->orderBy('sort_order')->get() as $field){
                    $value = ($employer->employerFields()->where('id',$field->id)->first()) ? $employer->employerFields()->where('id',$field->id)->first()->pivot->value:'';

                    if($field->type=='checkbox'){
                        $csvData[] = boolToString($value);
                    }
                    else{
                        $csvData[] = $value ;
                    }
                }

            }


            fputcsv($myfile,$csvData );
        }

        fclose($myfile);
        header('Content-type: text/csv');
        // It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.__('site.employer').'_'.__('site.export').'_'.date('r').'.csv"');

        // The PDF source is in original.pdf
        readfile($file);
        unlink($file);
        exit();


    }


    private function getEmployers(Request $request){
        $keyword = $request->get('search');


        $params = $request->all();


        if (!empty($keyword)) {

            $employers = User::where('role_id',2)->has('employer')->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);

        } else {
            $employers = User::where('role_id',2)->has('employer');
        }

        //create sorting feature
        $sort = $request->sort;

        switch($sort){
            case 'a':
                $employers = $employers->orderBy('name','asc');
                break;
            case 'd':
                $employers = $employers->orderBy('name','desc');
                break;
            case 'n':
                $employers = $employers->orderBy('id','desc');
                break;
            case 'o':
                $employers = $employers->orderBy('id','asc');
                break;
            default:
                $employers = $employers->orderBy('name');
                break;
        }






        if(isset($params['status']) && $params['status'] != '' )
        {
            $employers = $employers->where('status',$params['status']);
        }

        if(isset($params['gender']) && $params['gender'] != ''){
            $employers = $employers->whereHas('employer',function (Builder $query) use($params) {
                $query->where('gender',$params['gender']);
            });
        }




        if(isset($params['active']) &&  $params['active'] != '' ){
            $employers = $employers->whereHas('employer',function (Builder $query) use($params)  {
                $query->where('active',$params['active']);
            });
        }




        if(isset($params['field_id']) && isset($params['custom_field'])){
            $employers = $employers->whereHas('employerFields',function(Builder $query) use ($params) {
                $query->whereRaw("match(value) against (? IN NATURAL LANGUAGE MODE)", [$params['custom_field']]);
            });
        }


        return $employers;

    }

    private function getAllFields(){
        $fields = [];
        foreach(\App\EmployerFieldGroup::orderBy('sort_order')->get() as $group){
            foreach($group->employerFields()->orderBy('sort_order')->get() as $field){
                $fields[] = $field;
            }
        }
        return $fields;
    }

}
