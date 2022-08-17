@extends('layouts.admin-page')

@section('pageTitle',__('site.candidates'))
@section('page-title',__('site.profile').': '.$candidate->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        @can('access','view_candidates')
                        <a href="{{ url('/admin/candidates') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_candidate')
                        <a href="{{ url('/admin/candidates/' . $candidate->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_candidate')
                        <form method="POST" action="{{ url('admin/candidates' . '/' . $candidate->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan

                        <br/>
                        <br/>

                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            @lang('site.general-details')
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">

                                        <div class="row">
                                        <div class=" col-md-6 {{ $errors->has('name') ? 'has-error' : ''}}">
                                            <label for="name" class="control-label">@lang('site.name')</label>
                                           <div>{{ $candidate->name }}</div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('display_name') ? 'has-error' : ''}}">
                                            <label for="display_name" class="control-label">@lang('site.display-name')</label>
                                            <div>{{ $candidate->candidate->display_name }}</div>
                                        </div>
                                    </div>

                                        <div class="row">
                                        <div class="col-md-6 {{ $errors->has('email') ? 'has-error' : ''}}">
                                            <label for="email" class="control-label">@lang('site.email')</label>
                                            <div>{{ $candidate->email }}</div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('telephone') ? 'has-error' : ''}}">
                                            <label for="telephone" class="control-label">@lang('site.telephone')</label>
                                            <div>{{ $candidate->telephone }}</div>
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-6 {{ $errors->has('gender') ? 'has-error' : ''}}">
                                            <label for="gender" class="control-label">@lang('site.gender')</label>
                                            <div>{{ gender($candidate->candidate->gender) }}</div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('date_of_birth') ? 'has-error' : ''}}">
                                            <label for="date_of_birth" class="control-label">@lang('site.date-of-birth') (DD/MM/YYYY)</label>
                                            <div>{{ \Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->day }}/{{ \Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->month }}/{{ \Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->year }} ({{  getAge(\Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->timestamp) }})</div>

                                        </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-md-6">
                                                @if(!empty($candidate->candidate->picture))
                                                    <label for="date_of_birth" class="control-label">@lang('site.picture')</label>

                                                    <div><img   data-toggle="modal" data-target="#pictureModal" src="{{ asset($candidate->candidate->picture) }}" class="int_thmwpoin" /></div> <br/>



                                                    <div class="modal fade" id="pictureModal" tabindex="-1" role="dialog" aria-labelledby="pictureModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="pictureModalLabel">@lang('site.picture')</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body int_txcen" >
                                                                    <img src="{{ asset($candidate->candidate->picture) }}" class="int_txcen" />
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <label for="video_code">@lang('site.video')</label>
                                                @if(!empty($candidate->candidate->video_code))
                                                    {!! clean( $candidate->candidate->video_code ) !!}
                                                @endif
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="{{ 'cv_path' }}">@lang('site.cv-resume'):</label>


                                                @if(!empty($candidate->candidate->cv_path))
                                                    <h3>{{ basename($candidate->candidate->cv_path) }}</h3>
                                                    @if(isImage($candidate->candidate->cv_path))
                                                        <div><img  data-toggle="modal" data-target="#pictureModalcv" src="{{ route('admin.image') }}?file={{ $candidate->candidate->cv_path }}"  class="int_w330cur" /></div> <br/>


                                                        <div class="modal fade" id="pictureModalcv" tabindex="-1" role="dialog" aria-labelledby="pictureModalcvLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="pictureModalcvLabel">@lang('site.picture')</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body int_txcen"  >
                                                                        <img src="{{ route('admin.image') }}?file={{ $candidate->candidate->cv_path }}" class="int_txcen" />
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    @endif
                                                    <a class="btn btn-success" href="{{ route('admin.download') }}?file={{ $candidate->candidate->cv_path }}"><i class="fa fa-download"></i> @lang('site.download')</a>
                                                @endif
                                            </div>




                                        </div>

                                                <div class="row">
                                                        <div class="col-md-6 {{ $errors->has('employed') ? 'has-error' : ''}}">
                                                            <label for="employed" class="control-label">@lang('site.employed')</label>
                                                            <div>{{ boolToString($candidate->candidate->employed) }}</div>
                                                        </div>
                                                        <div class="col-md-6 {{ $errors->has('shortlisted') ? 'has-error' : ''}}">
                                                            <label for="shortlisted" class="control-label">@lang('site.shortlisted')</label>
                                                            <div>{{ boolToString($candidate->candidate->shortlisted) }}</div>
                                                        </div>
                                                </div>

                                        <div class="row">
                                                        <div class="col-md-6 {{ $errors->has('locked') ? 'has-error' : ''}}">
                                                            <label for="locked" class="control-label">@lang('site.locked')</label>
                                                            <div>{{ boolToString($candidate->candidate->locked) }}</div>

                                                        </div>
                                                        <div class="col-md-6 {{ $errors->has('status') ? 'has-error' : ''}}">
                                                            <label for="status" class="control-label">@lang('site.enabled')</label>
                                                            <div>{{ boolToString($candidate->status) }}</div>
                                                        </div>
                                        </div>
                                        <div class="row">
                                                        <div class="col-md-6 {{ $errors->has('public') ? 'has-error' : ''}}">
                                                            <label for="public" class="control-label">@lang('site.public')</label>
                                                            <div>{{ boolToString($candidate->candidate->public) }}</div>
                                                        </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            @foreach(\App\CandidateFieldGroup::get() as $group)
                                <div class="card">
                                    <div class="card-header" id="heading{{ $group->id }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $group->id }}" aria-expanded="false" aria-controls="collapse{{ $group->id }}">
                                                {{ $group->name }}
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapse{{ $group->id }}" class="collapse" aria-labelledby="heading{{ $group->id }}" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="row">
                                            @foreach($group->candidateFields()->orderBy('sort_order')->get() as $field)
                                                <?php


                                                    $value = ($candidate->candidateFields()->where('id',$field->id)->first()) ? $candidate->candidateFields()->where('id',$field->id)->first()->pivot->value:'';
                                            ?>

                                                @if($field->type=='text')
                                                    <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                        <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                        <div>{{ $value }}</div>
                                                    </div>
                                                @elseif($field->type=='select')
                                                    <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                        <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                        <div>{{ $value }}</div>

                                                    </div>
                                                @elseif($field->type=='textarea')
                                                    <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                        <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                      <div>{{ $value }}</div>
                                                    </div>
                                                @elseif($field->type=='checkbox')
                                                        <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                            <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                            <div>{{ boolToString($value) }}</div>
                                                        </div>

                                                @elseif($field->type=='radio')
                                                        <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                            <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                            <div>{{ $value }}</div>

                                                        </div>
                                                @elseif($field->type=='file')


                                                        <div class="col-md-6">
                                                            <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>


                                                            @if(!empty($value))
                                                                <h3>{{ basename($value) }}</h3>
                                                                @if(isImage($value))
                                                                    <div><img  data-toggle="modal" data-target="#pictureModal{{ $field->id }}" src="{{ route('admin.image') }}?file={{ $value }}"  class="int_w330cur" /></div> <br/>


                                                                    <div class="modal fade" id="pictureModal{{ $field->id }}" tabindex="-1" role="dialog" aria-labelledby="pictureModal{{ $field->id }}Label" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="pictureModal{{ $field->id }}Label">@lang('site.picture')</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body int_txcen"  >
                                                                                    <img src="{{ route('admin.image') }}?file={{ $value }}" class="int_txcen" />
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                @endif
                                                                 <a class="btn btn-success" href="{{ route('admin.download') }}?file={{ $value }}"><i class="fa fa-download"></i> @lang('site.download')</a>
                                                            @endif
                                                        </div>


                                                @endif


                                            @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/candidateshow.css') }}">

    @endsection
