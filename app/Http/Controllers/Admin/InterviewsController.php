<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Interview;
use App\Lib\HelperTrait;
use App\Mail\InterviewAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InterviewsController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_interviews');
        $keyword = $request->get('search');
        $perPage = 25;
        $params = $request->all();

        if (!empty($keyword)) {
            $interviews = Interview::where(function($query) use ($keyword) {
                $query->whereHas('user',function($query) use($keyword){
                    $query->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                })->orWhere('id','LIKE',"%{$keyword}%");
            });
        } else {
            $interviews = Interview::where('id','>',0);
        }

        $title = __('site.interviews');
        //filter by status
        if(isset($params['type']) && $params['type'] != '' )
        {
            if($params['type']=='u'){
                $type = __('site.upcoming');
                $interviews = $interviews->where('interview_date','>=',Carbon::now()->toDateString())->orderBy('interview_date','asc');
            }
            else{
                $type = __('site.past');
                $interviews = $interviews->where('interview_date','<',Carbon::now()->toDateString())->orderBy('interview_date','desc');
            }
            $title = $type.' '.$title;
        }
        else{
            $interviews = $interviews->latest();
        }

        //filter by min_date
        if(isset($params['min_date']) && $params['min_date'] != '' )
        {
            $interviews = $interviews->where('interview_date','>=',$params['min_date']);
        }

        //filter by max_date
        if(isset($params['max_date']) && $params['max_date'] != '' )
        {
            $interviews = $interviews->where('interview_date','<=',Carbon::parse($params['max_date'].' 23:59:59')->toDateTimeString());
        }

        $interviews = $interviews->paginate($perPage);

        unset($params['search'],$params['page'],$params['field_id']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;
        }


        return view('admin.interviews.index', compact('interviews','title','perPage','filterParams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_interview');
        return view('admin.interviews.create');
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
        $this->authorize('access','create_interview');
        $this->validate($request,[
           'user_id'=>'required',
           'interview_date'=>'required'
        ]);
        $requestData = $request->all();

        $requestData['hash'] = Str::random(30);
        
        $interview= Interview::create($requestData);

        //sync candidates
        if(!empty($request->candidates)){
            $interview->candidates()->attach($requestData['candidates']);
        }

        //send mail to employer
        if($interview->reminder==1){
            try{
                Mail::to($interview->user->email)->send(New InterviewAlert($interview));
            }
            catch(\Exception $ex){
                $this->warningMessage(__('site.mail-error').': '.$ex->getMessage());
            }
        }

        return redirect('admin/interviews')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_interview');
        $interview = Interview::findOrFail($id);

        return view('admin.interviews.show', compact('interview'));
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
        $this->authorize('access','edit_interview');
        $interview = Interview::findOrFail($id);

        return view('admin.interviews.edit', compact('interview'));
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
        $this->authorize('access','edit_interview');
        $requestData = $request->all();
        
        $interview = Interview::findOrFail($id);
        $interview->update($requestData);

        $interview->candidates()->sync($request->candidates);
        //send mail to employer
        if($interview->reminder==1){
            try{
                Mail::to($interview->user->email)->send(New InterviewAlert($interview));
            }
            catch(\Exception $ex){
                $this->warningMessage(__('site.mail-error').': '.$ex->getMessage());
            }
        }

        return redirect('admin/interviews')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_interview');
        Interview::destroy($id);

        return redirect('admin/interviews')->with('flash_message', __('site.record-deleted'));
    }
}
