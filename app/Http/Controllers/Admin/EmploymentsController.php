<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Employment;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmploymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request,User $user)
    {
        $this->authorize('access','view_employments');
        $perPage = 25;


        $employments = $user->employer->employments()->latest()->paginate($perPage);


        return view('admin.employments.index', compact('employments','user','perPage'));
    }

    public function browse(Request $request)
    {
        $this->authorize('access','view_employments');
        $keyword = $request->get('search');
        $perPage = 25;

        $params = $request->all();

        if (!empty($keyword)) {
    /*        $employments = Employment::whereHas('employer',function(Builder $query) use ($keyword){
                $query->whereHas('user',function(Builder $query) use ($keyword) {
                    $query->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                });
            })->orWhereHas('candidate',function(Builder $query) use ($keyword){
                $query->whereHas('user',function(Builder $query) use ($keyword) {
                    $query->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                });
            });*/


            $employments = Employment::where(function($query) use($keyword){
                $query->whereHas('employer',function(Builder $query) use ($keyword){
                    $query->whereHas('user',function(Builder $query) use ($keyword) {
                        $query->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                    });
                })->orWhereHas('candidate',function(Builder $query) use ($keyword){
                    $query->whereHas('user',function(Builder $query) use ($keyword) {
                        $query->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
                    });
                });
            });



        } else {
            $employments = Employment::orderBy('updated_at','desc');
        }


        if(isset($params['active']) && $params['active'] != '' )
        {
            $employments = $employments->where('active',$params['active']);
        }

        if(isset($params['min_salary']) && $params['min_salary'] != '' )
        {
            $employments = $employments->where('salary','>=',$params['min_salary']);
        }

        if(isset($params['max_salary']) && $params['max_salary'] != '' )
        {
            $employments = $employments->where('salary','<=',$params['max_salary']);
        }


        $employments = $employments->paginate($perPage);

        unset($params['search'],$params['page']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;
        }

        return view('admin.employments.browse', compact('employments','perPage','filterParams'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(User $user)
    {
        $this->authorize('access','create_employment');
        return view('admin.employments.create',compact('user'));
    }


    public function createEmployment()
    {
        $this->authorize('access','create_employment');
        return view('admin.employments.create-employment');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,User $user)
    {
        $this->authorize('access','create_employment');

        $this->validate($request,[
            'user_id'=>'required',
           'start_date'=>'required',
            'active'=>'required'
        ]);

        $requestData = $request->all();
        $requestData['employer_id'] = $user->employer->id;
        $requestData['candidate_id'] = User::find($requestData['user_id'])->candidate->id;
        
        Employment::create($requestData);

        return redirect()->route('admin.employments.index',['user'=>$user->id])->with('flash_message', __('site.changes-saved'));
    }

    public function storeEmployment(Request $request)
    {
        $this->authorize('access','create_employment');

        $this->validate($request,[
            'employer_user_id'=>'required',
            'user_id'=>'required',
            'start_date'=>'required',
            'active'=>'required'
        ]);

        $requestData = $request->all();
        $requestData['employer_id'] = User::find($requestData['employer_user_id'])->employer->id;
        $requestData['candidate_id'] = User::find($requestData['user_id'])->candidate->id;

        Employment::create($requestData);

        return redirect()->route('admin.employments.browse')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_employment');
        $employment = Employment::findOrFail($id);

        return view('admin.employments.show', compact('employment'));
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
        $this->authorize('access','edit_employment');
        $employment = Employment::findOrFail($id);

        return view('admin.employments.edit', compact('employment'));
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
        $this->authorize('access','edit_employment');
        $this->validate($request,[
            'user_id'=>'required',
            'start_date'=>'required',
            'active'=>'required'
        ]);

        $requestData = $request->all();
        
        $employment = Employment::findOrFail($id);
        $employment->update($requestData);

        return redirect()->route('admin.employments.index',['user'=>$employment->employer->user_id])->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_employment');
        $userId = Employment::find($id)->employer->user_id;
        Employment::destroy($id);

        return redirect()->route('admin.employments.index',['user'=>$userId])->with('flash_message', __('site.record-deleted'));
    }




}
