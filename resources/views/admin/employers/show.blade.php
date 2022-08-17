@extends('layouts.admin-page')

@section('pageTitle',__('site.employers'))
@section('page-title',__('site.profile').': '.$employer->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        @can('access','view_employers')
                        <a href="{{ url('/admin/employers') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_employer')
                        <a href="{{ url('/admin/employers/' . $employer->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_employer')
                        <form method="POST" action="{{ url('admin/employers' . '/' . $employer->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
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
                                           <div>{{ $employer->name }}</div>
                                        </div>
                                            <div class="col-md-6 {{ $errors->has('gender') ? 'has-error' : ''}}">
                                                <label for="gender" class="control-label">@lang('site.gender')</label>
                                                <div>{{ gender($employer->employer->gender) }}</div>
                                            </div>
                                    </div>

                                        <div class="row">
                                        <div class="col-md-6 {{ $errors->has('email') ? 'has-error' : ''}}">
                                            <label for="email" class="control-label">@lang('site.email')</label>
                                            <div>{{ $employer->email }}</div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('telephone') ? 'has-error' : ''}}">
                                            <label for="telephone" class="control-label">@lang('site.telephone')</label>
                                            <div>{{ $employer->telephone }}</div>
                                        </div>
                                        </div>


                                        <div class="row">
                                        <div class="col-md-6 {{ $errors->has('gender') ? 'has-error' : ''}}">
                                            <label for="gender" class="control-label">@lang('site.gender')</label>
                                            <div>{{ gender($employer->employer->gender) }}</div>
                                        </div>
                                            <div class="col-md-6 {{ $errors->has('active') ? 'has-error' : ''}}">
                                                <label for="active" class="control-label">@lang('site.active')</label>
                                                <div>{{ boolToString($employer->employer->active) }}</div>
                                            </div>

                                        </div>




                                                <div class="row">
                                                        <div class="col-md-6">
                                                            <label  class="control-label">@lang('site.registered-on')</label>
                                                            <div>{{ \Illuminate\Support\Carbon::parse($employer->created_at)->format('d/M/Y') }}</div>
                                                        </div>

                                                </div>


                                    </div>
                                </div>
                            </div>
                            @foreach(\App\EmployerFieldGroup::get() as $group)
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
                                            @foreach($group->employerFields()->orderBy('sort_order')->get() as $field)
                                                <?php


                                                    $value = ($employer->employerFields()->where('id',$field->id)->first()) ? $employer->employerFields()->where('id',$field->id)->first()->pivot->value:'';
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
    <link rel="stylesheet" href="{{ asset('css/admin/showemployers.css') }}">


    @endsection
