@extends('layouts.admin-page')

@section('pageTitle',__('site.edit-contract'))
@section('page-title',$contract->name)

@section('page-content')
    @if($contract->enabled==0)
        <p class="alert alert-warning">@lang('site.disabled-contract')<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
    @endif
    <form method="POST" action="{{ url('/admin/contracts/' . $contract->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ url('/admin/contracts') }}" title="@lang('site.back')"><button type="button" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                            </div>
                            <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary float-right">
                                        <i class="fa fa-save"></i> {{  __('site.save')  }}
                                    </button>
                            </div>
                        </div>

                        <br />

                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-book"></i> {{ __('site.contract') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-cogs"></i> @lang('site.settings')</a>
                                </li>

                            </ul>
                            <div class="tab-content" id="myTabContent2">
                                <div class="tab-pane fade show active pt-3 " id="home3" role="tabpanel" aria-labelledby="home-tab3">

                                    <div class="row pb-1">
                                        <div class="col-md-4">
                                            <span id="template-loader"></span>
                                            <select class="form-control select2" name="template" id="template" placeholder="@lang('site.load-template')">
                                                <option>--@lang('site.load-template')--</option>
                                                @foreach($templates as $template)
                                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="dropdown">
                                                <button class="btn btn-block btn-primary dropdown-toggle" type="button" id="signatorydropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    @lang('site.signatory-placeholders')
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    <a class="dropdown-item">{{ __('site.signature') }}</a>

                                                    @foreach($users as $user)
                                                    <a class="dropdown-item placeholder-link" data-code="[{{ 'signature-'.$user->id }}]" href="javascript:;" >{{ $user->name }} ({{ roleName($user->role_id) }}) - <strong>[{{ 'signature-'.$user->id }}]</strong></a>
                                                    @endforeach
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item">{{ __('site.name') }}</a>

                                                    @foreach($users as $user)
                                                        <a class="dropdown-item placeholder-link" data-code="[{{ 'name-'.$user->id }}]" href="javascript:;" >{{ $user->name }} ({{ roleName($user->role_id) }}) - <strong>[{{ 'name-'.$user->id }}]</strong></a>
                                                    @endforeach
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item">{{ __('site.sign-date') }}</a>

                                                    @foreach($users as $user)
                                                        <a class="dropdown-item placeholder-link" data-code="[{{ 'date-'.$user->id }}]" href="javascript:;" >{{ $user->name }} ({{ roleName($user->role_id) }}) - <strong>[{{ 'date-'.$user->id }}]</strong></a>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                               <i class="fa fa-signature"></i>  @lang('site.add-signature')
                                            </button>
                                            <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
                                                      <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <h5 class="modal-title">@lang('site.sign-here')</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                          </div>
                                                          <div class="modal-body">
                                                              <div id="signatureBox" style="border: dotted 1px #CCCCCC;">
                                                               </div>
                                                          </div>
                                                          <div class="modal-footer bg-whitesmoke br">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('site.close')</button>
                                                            <button type="button" class="btn btn-primary" id="signatureBtn">@lang('site.insert')</button>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
                                        <textarea class="form-control" rows="5" name="content" type="textarea" id="content" >{{ old('content',$contract->content) }}</textarea>
                                        {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
                                    </div>



                                </div>
                                <div class="tab-pane fade  tab-padding" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">

                                 <div class="pr-4">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                                        <label for="name" class="control-label">{{ __('site.name') }}</label>
                                        <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($contract->name) ? $contract->name : '') }}" >
                                        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="form-group  {{ $errors->has('users') ? 'has-error' : ''}}">

                                        <label for="users">@lang('site.signatories')</label>

                                        <select required multiple name="users[]" id="users" class="form-control select2">
                                            <option></option>
                                            @foreach($contract->users as $user)
                                                <option  @if( (is_array(old('users')) && in_array(@$user->id,old('users')))  || (null === old('users')))
                                                         selected
                                                         @endif
                                                         value="{{ $user->id }}">{{ $user->name }} ({{ roleName($user->role_id) }}) &lt;{{ $user->email }}&gt;</option>
                                            @endforeach
                                        </select>
                                        <small>
                                            @lang('site.certificate-hint')
                                        </small>

                                        {!! clean( $errors->first('users', '<p class="help-block">:message</p>') ) !!}
                                    </div>
                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                                        <label for="description" class="control-label">{{ __('site.description') }} (@lang('site.optional'))</label>
                                        <textarea class="form-control" rows="5" name="description" type="textarea" id="description" >{{ old('description',isset($contract->description) ? $contract->description : '') }}</textarea>
                                        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="form-group {{ $errors->has('enabled') ? 'has-error' : ''}}">
                                        <label for="enabled" class="control-label">{{ __('site.enabled') }}</label>
                                        <select name="enabled" class="form-control" id="enabled" >
                                            @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true)  as $optionKey => $optionValue)
                                                <option value="{{ $optionKey }}" {{ ((null !== old('enabled',@$contract->enabled)) && old('enabled',@$contract->enabled) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('enabled', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                </div>

                            </div>





                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>

@endsection


@section('footer')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('/js/admin/contract-template.js') }}"></script>
    <script src="{{ asset('vendor/jsignature/libs/jSignature.min.js') }}"></script>
    <script  type="text/javascript">
        "use strict";

        $('#exampleModal').on('show.bs.modal', function (e) {
            var sigdiv =  $("#signatureBox").jSignature({color:"#000000",lineWidth:5,'UndoButton':true});
            $("#signatureBox").resize();
            $('#signatureBtn').click(function(){
                $('#exampleModal').modal('hide');
                let datapair = sigdiv.jSignature("getData", "svgbase64");
                let src = "data:" + datapair[0] + "," + datapair[1];
                let code = '<img style="max-height: 100px;max-width: 100px" src="'+src+'" />';
                $('textarea#content').summernote('editor.saveRange');
                $('textarea#content').summernote('editor.restoreRange');
                $('textarea#content').summernote('editor.focus');
                $('textarea#content').summernote('editor.pasteHTML',code);
            });
        });




        $('#users').select2({
            placeholder: "@lang('site.search-users')...",
            minimumInputLength: 3,
            ajax: {
                url: '{{ route('admin.users.search') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }

        });
        $('#template').select2();
        $('#template').change(function (){
            let id = $(this).val();
            if(!(id > 0)){
                return;
            }
            let status = confirm('{{ addslashes(__('site.template-warning')) }}');
            if(status == false){
                $('#template').val(null).trigger('change');
                return;
            }
            let url = '{{ url('admin/contracts/get-template') }}/'+id;
            let loader = '<img src="{{ asset('img/loader.gif') }}" />';
            $('#template-loader').html(loader);
            $.get(url,function(data){
                $('#template-loader').html('');
                $('textarea#content').summernote('code',data.content,{
                    height:500,
                    focus: true
                });
            });
        });
        $('.placeholder-link').click(function(){
           let code = $(this).attr('data-code');
           console.log(code);
            $('textarea#content').summernote('editor.saveRange');
            $('textarea#content').summernote('editor.restoreRange');
            $('textarea#content').summernote('editor.focus');
            $('textarea#content').summernote('editor.insertText',code);
        });
    </script>

@endsection
@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
@endsection
