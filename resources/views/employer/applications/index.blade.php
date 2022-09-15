@extends($userLayout)

@section('pageTitle',__('site.applications').': '.$vacancy->title)
@section('page-title',__('site.applications').' ('.$applications->count().')'.': '.$vacancy->title)

@section('content')
    <div>
        <div>
            @if(!empty($filterParams))
                <ul  class="list-inline">
                    <li class="list-inline-item" ><strong>@lang('site.filter'):</strong></li>
                    @foreach($filterParams as $param)
                        @if(null !== request()->get($param)  && request()->get($param) != '')
                            <li class="list-inline-item" >{{ ucwords(str_ireplace('_',' ',$param)) }}</li>
                        @endif
                    @endforeach

                </ul>
            @endif
        </div>
        <div>
            <a href="{{ url('/employer/vacancies') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
          
            <div class="collapse int_tm20" id="filterCollapse"  >
                <form action="{{ route('employer.applications.index',['vacancy'=>$vacancy->id]) }}" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search" class="control-label">@lang('site.search')</label>
                                <input class="form-control" type="text" value="{{ request()->search  }}" name="search"/>
                            </div>
                        </div>

                        <div class="form-group col-md-2 {{ $errors->has('gender') ? 'has-error' : ''}}">
                            <label for="gender" class="control-label">@lang('site.gender')</label>
                            <select name="gender" class="form-control" id="gender" >
                                <option></option>
                                @foreach (json_decode('{"f":"'.__('site.female').'","m":"'.__('site.male').'"}', true) as $optionKey => $optionValue)
                                    <option value="{{ $optionKey }}" {{ ((null !== old('gender',@request()->gender)) && old('gender',@request()->gender) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                @endforeach
                            </select>
                            {!! clean( $errors->first('gender', '<p class="help-block">:message</p>') ) !!}
                        </div>

                        <div class="form-group col-md-2">
                            <label for="search" class="control-label">@lang('site.min-age')</label>
                            <select class="form-control" name="min_age" id="min_age">
                                <option></option>
                                @foreach(range(1,100) as $value)
                                    <option @if(request()->min_age==$value) selected  @endif value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="search" class="control-label">@lang('site.max-age')</label>
                            <select class="form-control" name="max_age" id="max_age">
                                <option></option>
                                @foreach(range(1,100) as $value)
                                    <option @if(request()->max_age==$value) selected  @endif value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="field_id" class="control-label">@lang('site.custom-field')</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <select class="form-control" name="field_id" id="field_id">
                                        <option ></option>
                                        @foreach(\App\CandidateField::orderBy('sort_order')->where('type','!=','file')->get() as $field)
                                            <option @if($field->id==request()->get('field_id')) selected @endif value="{{ $field->id }}">{{ $field->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    =
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="custom_field" value="{{ request()->get('custom_field') }}"/>
                                </div>
                            </div>

                        </div>





                        <div class="form-group  col-md-2{{ $errors->has('shortlisted') ? 'has-error' : ''}}">
                            <label for="shortlisted" class="control-label">@lang('site.shortlisted')</label>
                            <select name="shortlisted" class="form-control" id="shortlisted" >
                                <option></option>
                                @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                                    <option value="{{ $optionKey }}" {{ ((null !== old('shortlisted',@request()->shortlisted)) && old('shortlisted',@request()->shortlisted) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                @endforeach
                            </select>
                            {!! clean( $errors->first('shortlisted', '<p class="help-block">:message</p>') ) !!}
                        </div>


                        <div class="col-md-2">

                            <div class="form-group">
                                <label for="min_date" class="control-label">@lang('site.min-date')</label>
                                <input class="form-control date" type="text" value="{{ request()->min_date  }}" name="min_date"/>
                            </div>

                        </div>
                        <div class="col-md-2">

                            <div class="form-group">
                                <label for="max_date" class="control-label">@lang('site.max-date')</label>
                                <input class="form-control date" type="text" value="{{ request()->max_date  }}" name="max_date"/>
                            </div>

                        </div>




                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary btn-block">@lang('site.filter')</button>
                    </div>

                </form>
            </div>
            <br/>
            <br/>
            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('site.picture')</th>
                            <th>@lang('site.candidate')</th>
                            <th>@lang('site.gender')</th>
                            <th>@lang('site.age')</th>
                            <th>@lang('site.shortlisted')</th>
                            <th>@lang('site.added-on')</th>
			    <th>@lang('site.status')</th>
                            <th>@lang('site.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($applications as $item)
                    <?php
                        if($item->status == 'pending'){
                            $status = 'site.pending';
                        }
                        if($item->status == 'allow'){
                            $status = 'site.allow';
                        }
                        if($item->status == 'deny'){
                            $status = 'site.deny';
                        }
                        if($item->status == 'Interview Planned'){
                            $status = 'site.interview_planned';
                        }
                        if($item->status == 'Placed'){
                            $status = 'site.placed';
                        }
                    ?>
                        <tr>
                            <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                            <td>
                                <div class="media align-items-center">
                                    <span class="avatar avatar-sm rounded-circle">
                                        @if(!empty($item->user->candidate->picture) && file_exists($item->user->candidate->picture))
                                            <img   src="{{ asset($item->user->candidate->picture) }}">
                                        @else
                                            <img   src="{{ asset('img/man.jpg') }}">
                                        @endif

                                    </span>
                                </div>
                            </td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ gender($item->user->candidate->gender) }}</td>
                            <td>{{  getAge(\Illuminate\Support\Carbon::parse($item->user->candidate->date_of_birth)->timestamp) }}</td>
                            <td>
                                {{ boolToString($item->shortlisted) }}
                            </td>
                            <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
			                <td>@lang($status)</td>
                            <td>
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ni ni-settings"></i> @lang('site.actions')
                                    </button>
                                    <div class="dropdown-menu">
                                        <!-- Dropdown menu links -->
					                    <a class="dropdown-item" href="#" onclick="$('#allowForm{{ $item->id }}').submit()">@lang('site.allow')</a>
                                        <a class="dropdown-item" href="#" onclick="$('#denyForm{{ $item->id }}').submit()">@lang('site.deny')</a>
                                        <a class="dropdown-item" href="{{ route('employer.create-interview', ['application' => $item->id]) }}">@lang('site.make-interview')</a>
                                        <a class="dropdown-item" href="{{ route('employer.profiles',['candidate'=>$item->user->candidate->id, 'application' => $item->id]) }}">@lang('site.view-candidate')</a>
                                        <a class="dropdown-item" href="{{ route('employer.create-placement', ['application' => $item->id]) }}">@lang('site.make-placement')</a>
                                        <a class="dropdown-item" href="{{ route('employer.applications.shortlist',['application'=>$item->id]) }}?status={{ $item->shortlisted==1? 0:1 }}">{{ $item->shortlisted==1? __('site.remove-from').' ':'' }}@lang('site.shortlist')</a>
                                        <a class="dropdown-item" href="{{ route('employer.candidates.tests',['user'=>$item->user_id]) }}" >@lang('site.test-results') ({{ $item->user->userTests()->count() }})</a>
                                    </div>
                                </div>
                                <form  onsubmit="return confirm(&quot; @lang('site.allow') &quot;)"   id="allowForm{{ $item->id }}"  method="POST" action="{{ url('/employer/applications/allow' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}
                                </form>
                                <form  onsubmit="return confirm(&quot; @lang('site.deny') &quot;)"   id="denyForm{{ $item->id }}"  method="POST" action="{{ url('/employer/applications/deny' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper"> {!! clean( $applications->appends(request()->input())->render() ) !!} </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/order-search.js') }}"></script>

@endsection


@section('header')
    @parent
    <link href="{{ asset('vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.time.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.css') }}" rel="stylesheet">


@endsection
