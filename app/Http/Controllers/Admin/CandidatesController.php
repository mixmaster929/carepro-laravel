<?php

namespace App\Http\Controllers\Admin;

use App\Candidate;
use App\CandidateField;
use App\CandidateFieldGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Lib\HelperTrait;
use App\Lib\ProfileGenerator;
use App\User;
use Embed\Embed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ParseCsv\Csv;

class CandidatesController extends Controller
{

    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_candidates');

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

        $candidates = $this->getCandidates($request);
        $total = $this->getCandidates($request)->count();

        $perPage = 25;
        $candidates = $candidates->paginate($perPage);

        $params = $request->all();
        unset($params['search'],$params['page'],$params['field_id'],$params['sort']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;//str_ireplace('_',' ',$key);
        }

        return view('admin.candidates.index', compact('candidates','filterParams','perPage','total','sort'));
    }

    private function getCandidates(Request $request){
        $keyword = $request->get('search');


        $params = $request->all();


        if (!empty($keyword)) {
            /* $candidates = User::where('role_id',3)->whereHas('candidate', function (Builder $query) use ($keyword) {
                 $query->whereRaw("match(display_name) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
             })->orWhereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);*/

            $candidates = User::where('role_id',3)->where(function ($query)  use ($keyword){
                $query->whereHas('candidate', function (Builder $query) use ($keyword) {
                    $query->whereRaw("match(display_name) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                })->orWhereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            })->orderBy('name');

        } else {
            $candidates = User::where('role_id',3)->has('candidate');
        }


        //create sorting feature
        $sort = $request->sort;
        switch($sort){
            case 'a':
                $candidates = $candidates->orderBy('name','asc');
                break;
            case 'd':
                $candidates = $candidates->orderBy('name','desc');
                break;
            case 'n':
                $candidates = $candidates->orderBy('id','desc');
                break;
            case 'o':
                $candidates = $candidates->orderBy('id','asc');
                break;
            default:
                $candidates = $candidates->orderBy('name');
                break;
        }


        if(isset($params['status']) && $params['status'] != '' )
        {
            $candidates = $candidates->where('status',$params['status']);
        }

        if(isset($params['category']) && $params['category'] != ''){
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use($params) {
                $query->whereHas('categories',function (Builder $query) use($params){
                    $query->where('id',$params['category']);
                });
            });
        }


        if(isset($params['gender']) && $params['gender'] != ''){
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use($params) {
                $query->where('gender',$params['gender']);
            });
        }


        if(isset($params['employed']) && $params['employed'] != ''){
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use($params) {
                $query->where('employed',$params['employed']);
            });
        }

        if(isset($params['shortlisted']) &&  $params['shortlisted'] != '' ){
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use($params)  {
                $query->where('shortlisted',$params['shortlisted']);
            });
        }

        if(isset($params['locked']) &&  $params['locked'] != ''){
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use($params)  {
                $query->where('locked',$params['locked']);
            });
        }

        if(isset($params['public']) && $params['public'] != ''){
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use($params)  {
                $query->where('public',$params['public']);
            });
        }

        //do age
        if(isset($params['min_age'])){
            $year = date('Y') - $params['min_age'];
            $minDate = $year.'-12-31';
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use ($minDate) {
                $query->where('date_of_birth','<=',$minDate);
            });
        }

        if(isset($params['max_age'])){
            $year = date('Y') - $params['max_age'];
            $maxDate = $year.'-01-01';
            $candidates = $candidates->whereHas('candidate',function (Builder $query) use ($maxDate) {
                $query->where('date_of_birth','>=',$maxDate);
            });
        }

        if(isset($params['field_id']) && isset($params['custom_field'])){
            $candidates = $candidates->whereHas('candidateFields',function(Builder $query) use ($params) {
                //  $query->where('value','LIKE','%'.$params['custom_field'].'%');
                $query->whereRaw("match(value) against (? IN NATURAL LANGUAGE MODE)", [$params['custom_field']]);
            });
        }

        return $candidates;

    }

    public function export(Request $request){
        $this->authorize('access','view_candidates');

        $candidates = $this->getCandidates($request);

        $file = "export.txt";
        if (file_exists($file)) {
            unlink($file);
        }



        $myfile = fopen($file, "w") or die("Unable to open file!");

        $columns = array('#',__('site.name'),__('site.display-name'),__('site.email'),__('site.telephone'),__('site.gender'),__('site.date-of-birth'),__('site.age'),__('site.picture'),__('site.employed'),__('site.shortlisted'),__('site.locked'),__('site.public'));

        $fields = CandidateField::orderBy('sort_order')->get();


        foreach(CandidateFieldGroup::orderBy('sort_order')->get() as $group){
            foreach($group->candidateFields()->orderBy('sort_order')->get() as $field){
                $columns[] = $field->name;
            }

        }



        fputcsv($myfile,$columns );

        foreach($candidates->cursor() as $candidate){
            $csvData = [$candidate->id,$candidate->name,$candidate->candidate->display_name,$candidate->email,$candidate->telephone,gender($candidate->candidate->gender),Carbon::parse($candidate->candidate->date_of_birth)->format('d/M/Y'),getAge(\Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->timestamp),empty($candidate->candidate->picture)?'':asset($candidate->candidate->picture),boolToString($candidate->candidate->employed),boolToString($candidate->candidate->shortlisted),boolToString($candidate->candidate->locked),boolToString($candidate->candidate->public)];


            foreach(CandidateFieldGroup::orderBy('sort_order')->get() as $group){
                foreach($group->candidateFields()->orderBy('sort_order')->get() as $field){
                    $value = ($candidate->candidateFields()->where('id',$field->id)->first()) ? $candidate->candidateFields()->where('id',$field->id)->first()->pivot->value:'';

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
        header('Content-Disposition: attachment; filename="'.__('site.candidate').'_'.__('site.export').'_'.date('r').'.csv"');

        // The PDF source is in original.pdf
        readfile($file);
        unlink($file);
        exit();


    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_candidate');
        return view('admin.candidates.create');
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
        $this->authorize('access','create_candidate');
        $messages=[];
        $rules = [
            'name'=>'required',
            'display_name'=>'required',
            'gender'=>'required',
            'email'=>'required|email|string|max:255|unique:users',
            'status'=>'required',
            'date_of_birth_year'=>'required',
            'date_of_birth_month'=>'required',
            'date_of_birth_day'=>'required',
            'picture' => 'nullable|max:'.config('app.upload_size').'|mimes:jpeg,png,gif',
            'cv_path' => 'nullable|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files'),
        ];

        foreach($this->getAllFields() as $field){

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

            if($field->required==1){
                $messages['field_'.$field->id.'.required'] = __('validation.required',['attribute'=>$field->name]);
            }
        }




        $this->validate($request,$rules,$messages);
        $requestData = $request->all();
        //create password
        $password= $request->password;
        if(empty($password)){
            $password = Str::random(8);
        }

        $requestData['password'] = Hash::make($password);
        $requestData['role_id'] = 3;

        //First create user
        $user= User::create($requestData);

        //Calculate date of birth
        $dateOfBirth = $request->date_of_birth_year.'-'.$request->date_of_birth_month.'-'.$request->date_of_birth_day;
        $requestData['date_of_birth'] = $dateOfBirth;

        //checkfor picture
        if($request->hasFile('picture')) {

            $path =  $request->file('picture')->store(CANDIDATES,'public_uploads');

            $file = UPLOAD_PATH.'/'.$path;
            $img = Image::make($file);

            $img->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($file);

            $requestData['picture'] = $file;
        }
        else{

            $requestData['picture'] =null;
        }

        if($request->hasFile('cv_path')) {

            //$path =  $request->file('cv_path')->store(CANDIDATES,'public_uploads');

            $name = $_FILES['cv_path']['name'];

            $extension = $request->cv_path->extension();
            //  dd($extension);

            $name = str_ireplace('.'.$extension,'',$name);

            $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

            $path =  $request->file('cv_path')->storeAs(CANDIDATE_FILES,$name,'public_uploads');



            $file = UPLOAD_PATH.'/'.$path;

            $requestData['cv_path'] = $file;
        }
        else{

            $requestData['cv_path'] =null;
        }


        //check for video
        if(!empty($requestData['video_url'])){
            try{
                $info = Embed::create($requestData['video_url']);

                if($info->type == 'video'){
                    $requestData['video_code'] = $info->code;
                }
            }
            catch(\Exception $ex){
                $this->warningMessage($ex->getMessage());
            }

        }

        $user->candidate()->create($requestData);

        //save categories
        $user->candidate->categories()->attach($request->categories);

        //now save custom fields
        $fields = CandidateField::get();

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

                        $path =  $request->file('field_'.$field->id)->storeAs(CANDIDATE_FILES,$name,'public_uploads');



                        $file = UPLOAD_PATH.'/'.$path;
                        $customValues[$field->id] = ['value'=>$file];
                    }
                }
                else{
                    $customValues[$field->id] = ['value'=>$requestData['field_'.$field->id]];
                }



            }


        }

        $user->candidateFields()->sync($customValues);

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


        return redirect('admin/candidates')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_candidate');
        $candidate = User::findOrFail($id);

        return view('admin.candidates.show', compact('candidate'));
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
        $this->authorize('access','edit_candidate');
        $candidate = User::findOrFail($id);
        return view('admin.candidates.edit', compact('candidate'));
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

        $this->authorize('access','edit_candidate');

        $rules = [
            'name'=>'required',
            'display_name'=>'required',
            'gender'=>'required',
            'email'=>'required|email|string|max:255',
            'status'=>'required',
            'date_of_birth_year'=>'required',
            'date_of_birth_month'=>'required',
            'date_of_birth_day'=>'required',
            'picture' => 'nullable|max:'.config('app.upload_size').'|mimes:jpeg,png,gif',
            'cv_path' => 'nullable|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files'),
        ];

        $user = User::find($id);
        if($user->email != $request->email){
            $rules['email'] = 'required|email|string|max:255|unique:users';
        }


        foreach($this->getAllFields() as $field){

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

        //save categories
        $user->candidate->categories()->sync($request->categories);

        $dateOfBirth = $request->date_of_birth_year.'-'.$request->date_of_birth_month.'-'.$request->date_of_birth_day;
        $requestData['date_of_birth'] = $dateOfBirth;

        //checkfor picture
        if($request->hasFile('picture')) {
            @unlink($user->candidate->picture);
            $path =  $request->file('picture')->store(CANDIDATES,'public_uploads');

            $file = UPLOAD_PATH.'/'.$path;
            $img = Image::make($file);

            $img->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($file);

            $requestData['picture'] = $file;
        }
        else{

            $requestData['picture'] = $user->candidate->picture;
        }

        if($request->hasFile('cv_path')) {
            @unlink($user->candidate->cv_path);
            $name = $_FILES['cv_path']['name'];

            $extension = $request->cv_path->extension();
            //  dd($extension);

            $name = str_ireplace('.'.$extension,'',$name);

            $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

            $path =  $request->file('cv_path')->storeAs(CANDIDATE_FILES,$name,'public_uploads');

            $file = UPLOAD_PATH.'/'.$path;

            $requestData['cv_path'] = $file;
        }
        else{

            $requestData['cv_path'] = $user->candidate->cv_path;
        }

        $candidateRecord = Candidate::where('user_id',$user->id)->first();
        $candidateRecord->update($requestData);
        //$user->candidate()->update($requestData);


        //now save custom fields
        $fields = CandidateField::get();

        $customValues = [];
        //attach custom values
        foreach($fields as $field){
            if(isset($requestData['field_'.$field->id]) || $field->type=='file'){
                if($field->type=='file'){
                    if($request->hasFile('field_'.$field->id)){
                        //get current file name
                        if($user->candidateFields()->where('id',$field->id)->first())
                        {
                            $fileName = $user->candidateFields()->where('id',$field->id)->first()->pivot->value;
                            @unlink($fileName);
                        }

                        //generate name for file

                        $name = $_FILES['field_'.$field->id]['name'];

                        //dd($name);


                        $extension = $request->{'field_'.$field->id}->extension();
                        //  dd($extension);

                        $name = str_ireplace('.'.$extension,'',$name);

                        $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

                        $path =  $request->file('field_'.$field->id)->storeAs(CANDIDATE_FILES,$name,'public_uploads');



                        $file = UPLOAD_PATH.'/'.$path;
                        $customValues[$field->id] = ['value'=>$file];
                    }
                    elseif($user->candidateFields()->where('id',$field->id)->first()){
                        $fileName = $user->candidateFields()->where('id',$field->id)->first()->pivot->value;
                        $customValues[$field->id] = ['value'=>$fileName];
                    }
                }
                else{
                    $customValues[$field->id] = ['value'=>$requestData['field_'.$field->id]];
                }
            }


        }

        $user->candidateFields()->sync($customValues);




        return redirect('admin/candidates')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_candidate');

        //Clean up all files for candidate


        User::destroy($id);

        return redirect('admin/candidates')->with('flash_message', __('site.record-deleted'));
    }


    public function removePicture($id){
        $this->authorize('access','edit_candidate');
        $user = User::find($id);
        @unlink($user->candidate->picture);
        $user->candidate->picture = '';
        $user->candidate->save();
        return back()->with('flash_message',__('site.picture').' '.__('site.deleted'));
    }

    public function removeCv($id){
        $this->authorize('access','edit_candidate');
        $user = User::find($id);
        @unlink($user->candidate->cv_path);
        $user->candidate->cv_path= '';
        $user->candidate->save();
        return back()->with('flash_message',__('site.file').' '.__('site.deleted'));
    }

    public function removeFile($fieldId,$userId){
        $this->authorize('access','edit_candidate');
        $user = User::find($userId);
        $file = $user->candidateFields()->where('id',$fieldId)->first()->pivot->value;
        @unlink($file);
        $user->candidateFields()->detach($fieldId);
        return back()->with('flash_message',__('site.picture').' '.__('site.deleted'));
    }


    public function download($id,$full=0){
        $profileGenerator = new ProfileGenerator();
        if($full==0){
            $full = false;
        }
        else{
            $full= true;
        }

        return   $profileGenerator->createPdf($id,false,$full);
    }

    public function search(Request $request){
        $keyword = $request->get('term');

        if(empty($keyword)){
            return response()->json([]);
        }

        $candidates = User::where('role_id',3)->where(function ($query)  use ($keyword){
/*            $query->whereHas('candidate', function (Builder $query) use ($keyword) {
                $query->whereRaw("match(display_name) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            })->orWhereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);*/

            $query->whereHas('candidate', function (Builder $query) use ($keyword) {
            //    $query->whereRaw("match(display_name) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                $query->where('display_name','LIKE','%'.$keyword.'%');

            })->orWhere(function($query) use($keyword){
                $query->where('name','LIKE','%'.$keyword.'%')->orWhere('email','LIKE','%'.$keyword.'%')->orWhere('telephone','LIKE','%'.$keyword.'%');
            });

        })->limit(500)->get();

        $formattedUsers = [];

        foreach($candidates as $candidate){


            if(isset($candidate->candidate)){
                if($request->get('format')=='number'){
                    $formattedUsers[] = ['id'=>$candidate->id,'text'=>"{$candidate->name} ({$candidate->telephone})"];
                }
                elseif($request->get('format')=='candidate_id'){
                    $formattedUsers[] = ['id'=>$candidate->candidate->id,'text'=>"{$candidate->name} <{$candidate->email}>"];

                }
                else{
                    $formattedUsers[] = ['id'=>$candidate->id,'text'=>"{$candidate->name} <{$candidate->email}>"];
                }
            }


        }


        // $formattedUsers['pagination']=['more'=>false];
        return response()->json($formattedUsers);
    }

    public function importForm(){
        $this->authorize('access','create_candidate');
        return view('admin.candidates.import1',['active'=>1]);
    }

    public function importUpload(Request $request){
        $this->authorize('access','create_candidate');
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
        session(['import-csv'=>$newName]);
        return redirect()->route('admin.candidates.import-2');

    }

    public function importFields(){
        $this->authorize('access','create_candidate');
        $file = session()->get('import-csv');
        if(empty($file) || !file_exists($file)){
            return redirect()->route('admin.candidates.import-1')->with('flash_message',__('site.upload-csv'));
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


        return view('admin.candidates.import2',['active'=>2,'columns'=>$columns,'options'=>$options,'required'=>$required]);
    }

    public function saveImportFields(Request $request){
        $this->authorize('access','create_candidate');

        $rules = [
            'name'=>'required',
            'email'=>'required',
        ];

        foreach(CandidateField::where('type','!=','file')->get() as $field){

             if($field->required==1){
                $rules['field_'.$field->id] = 'required';
            }
        }

        $data = $request->all();
        unset($data['_token']);
        $data = serialize($data);
        session()->put('import-fields',$data);
        return redirect()->route('admin.candidates.import-preview');

    }

    public function importPreview(){
        $this->authorize('access','create_candidate');
        $file = session()->get('import-csv');
        if(empty($file) || !file_exists($file)){
            return redirect()->route('admin.candidates.import-1')->with('flash_message',__('site.upload-csv'));
        }

        $fields = session()->get('import-fields');
        if(empty($fields)){
            return redirect()->route('admin.candidates.import-2')->with('flash_message',__('site.set-fields'));
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

        return view('admin.candidates.import3',['active'=>3,'columns'=>$columns,'values'=>$values,'fields'=>$fields]);
    }

    private function getImportFields(){

        $columns = [
            'name'=>__('site.name'),
            'last_name'=>__('site.last-name'),
            'display_name'=>__('site.display-name'),
            'email'=>__('site.email'),
            'telephone'=>__('site.telephone'),
            'gender'=>__('site.gender'),
            'date_of_birth'=>__('site.date-of-birth'),
            'age'=>__('site.age'),
            'picture'=>__('site.picture'),
            'employed'=>__('site.employed'),
            'shortlisted'=>__('site.shortlisted'),
            'locked'=>__('site.locked'),
            'public'=>__('site.public')
        ];

        $required = ['name'=>'name','email'=>'email'];


        foreach(CandidateFieldGroup::orderBy('sort_order')->get() as $group){
            foreach($group->candidateFields()->where('type','!=','file')->orderBy('sort_order')->get() as $field){
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
        $this->authorize('access','create_candidate');
        $file = session()->get('import-csv');
        if(empty($file) || !file_exists($file)){
            return redirect()->route('admin.candidates.import-1')->with('flash_message',__('site.upload-csv'));
        }

        $fields = session()->get('import-fields');
        if(empty($fields)){
            return redirect()->route('admin.candidates.import-2')->with('flash_message',__('site.set-fields'));
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
            try{


            $candidateData=[];
            foreach($fields as $key=>$value){
                $candidateData[$key] = @$requestData[$value];
            }

            if(empty($candidateData['name']) || empty($candidateData['email'])){
                continue;
            }


            //now set candidate data
            if(empty($candidateData['display_name'])){
                $candidateData['display_name'] = $candidateData['name'];
            }

            if(!empty($candidateData['last_name'])){
                $candidateData['name'] = $candidateData['name'].' '.$candidateData['last_name'];
            }

            $candidateData['gender'] = strtolower(substr($candidateData['gender'],0,1));

            if(!empty($candidateData['date_of_birth']) && strtotime($candidateData['date_of_birth'])){
                $candidateData['date_of_birth'] = Carbon::parse($candidateData['date_of_birth'])->toDateTimeString();
            }
            elseif(!empty($candidateData['age']) && intval($candidateData['age']) > 0){
                $candidateData['date_of_birth'] = Carbon::now()->subYears(intval($candidateData['age']))->toDateTimeString();
            }
            else{
                $candidateData['date_of_birth'] = Carbon::now()->toDateTimeString();
            }

            $candidateData['employed'] = stringToBool($candidateData['employed']);
            $candidateData['shortlisted'] = stringToBool($candidateData['shortlisted']);
            $candidateData['locked'] = stringToBool($candidateData['locked']);
            $candidateData['public'] = stringToBool($candidateData['public']);


            //download picture

                if(!empty($candidateData['picture'])){
                    $url = $candidateData['picture'];
                    $remoteName = basename($url);
                    try{
                            $size = curl_get_file_size($candidateData['picture']);
                            if($size < 10000000){
                                $filename = time().'-'.$remoteName;
                                $tempImage = tempnam(sys_get_temp_dir(), $filename);
                                copy($url, $tempImage);

                                $path_parts = pathinfo($candidateData['picture']);

                                $extension = $path_parts['extension'];


                                $file = UPLOAD_PATH.'/'.CANDIDATES.'/'.uniqid().'.'.$extension;
                                $img = Image::make($tempImage);

                                $img->resize(500, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });
                                $img->save($file);

                                $candidateData['picture'] = $file;
                                @unlink($tempImage);
                            }

                        }
                        catch(\Exception $ex){
                            $this->errorMessage($ex->getMessage());
                        }
                }





            if(User::where('email',$candidateData['email'])->exists()){
                if($request->update==1){
                    $user = User::where('email',$candidateData['email'])->first();
                    if(!empty($candidateData['picture'])){
                        @unlink($user->candidate->picture);
                    }
                    $user->update($candidateData);
                    //$user->candidate()->update($candidateData);

                    $candidateRecord = Candidate::where('user_id',$user->id)->first();
                    if($candidateRecord){
                        $candidateRecord->update($candidateData);
                    }
                    else{
                        $user->candidate()->create($candidateData);
                    }


                    $cfields = CandidateField::where('type','!=','file')->get();
                    $customValues = [];
                    foreach($cfields as $field){
                        $customValues[$field->id] = ['value'=>$candidateData['field_'.$field->id]];
                    }
                    $user->candidateFields()->sync($customValues);

                    $totalUpdate++;


                }

            }
            else{
                $password = Str::random(8);
                $candidateData['password'] = Hash::make($password);
                $candidateData['role_id']= 3;
                $user= User::create($candidateData);
                $user->candidate()->create($candidateData);


                $cfields = CandidateField::where('type','!=','file')->get();
                $customValues = [];
                foreach($cfields as $field){
                    $customValues[$field->id] = ['value'=>$candidateData['field_'.$field->id]];
                }
                $user->candidateFields()->sync($customValues);
                $totalImport++;

                //notify
                if($request->notify==1){
                    $message = __('mails.new-account',[
                        'siteName'=>setting('general_site_name'),
                        'email'=>$candidateData['email'],
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
            catch(\Exception $ex){
                $this->errorMessage($ex->getMessage());
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

        return redirect()->route('admin.candidates.import-complete');


    }

    public function completeImport(){
        $this->authorize('access','create_candidate');
        session()->forget(['import-csv','import-fields']);
        return view('admin.candidates.import4',['active'=>4]);

    }

    public function tests(User $user){
        $this->authorize('access','view_test_results');
        $perPage = 30;
        $tests = $user->userTests()->paginate($perPage);
        return view('admin.candidates.tests',compact('tests','perPage','user'));
    }

    private function getAllFields(){
        $fields = [];
        foreach(\App\CandidateFieldGroup::orderBy('sort_order')->get() as $group){
            foreach($group->candidateFields()->orderBy('sort_order')->get() as $field){
                $fields[] = $field;
            }
        }
        return $fields;
    }
}
