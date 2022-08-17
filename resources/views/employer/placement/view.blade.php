@extends($userLayout)

@section('page-title',__('site.placement').': '.$employment->candidate->user->name)
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('employer.placements') }}">@lang('site.placements')</a></li>
    <li class="breadcrumb-item">@lang('site.view')</li>
@endsection


@section('content')

    <div class="card bd-0">
        <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
            <nav class="nav nav-tabs">
                <a class="nav-link active" data-toggle="tab" href="#tabCont1">@lang('site.details')</a>
                <a class="nav-link" data-toggle="tab" href="#tabCont2">@lang('site.candidate')</a>
                <a id="commentTab" class="nav-link" data-toggle="tab" href="#tabCont4">@lang('site.comments') ({{ $employment->employmentComments()->count() }})</a>

            </nav>
        </div><!-- card-header -->
        <div class="card-body bd bd-t-0 tab-content">
            <div id="tabCont1" class="tab-pane active show">

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>@lang('site.candidate')</label>
                        <div>{{ $employment->candidate->user->name }}</div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang('site.start-date')</label>
                        <div>{{ \Illuminate\Support\Carbon::parse($employment->start_date)->format('d/M/Y') }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>@lang('site.end-date')</label>
                        <div>{{ \Illuminate\Support\Carbon::parse($employment->end_date)->format('d/M/Y') }}</div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang('site.active')</label>
                        <div>{{ boolToString($employment->active) }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>@lang('site.salary')</label>
                        <div>{{ price($employment->salary) }}</div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label></label>
                        <div></div>
                    </div>
                </div>


            </div><!-- tab-pane -->
            <div id="tabCont2" class="tab-pane">

                <div class="accordion accordion-dark" id="accordionExample" role="tablist" aria-multiselectable="true">
                    <div class="card">
                        <div class="card-header" role="tab"  id="headingOne">
                            <a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                @lang('site.general-details')
                            </a>
                        </div>

                        <div id="collapseOne" class="collapse show"  role="tabpanel"  aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">

                                <div class="row">
                                    <div class=" col-md-6  form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                                        <label for="name" class="control-label">@lang('site.name')</label>
                                        <div>{{ $candidate->name }}</div>
                                    </div>
                                    <div class="col-md-6  form-group {{ $errors->has('display_name') ? 'has-error' : ''}}">
                                        <label for="display_name" class="control-label">@lang('site.display-name')</label>
                                        <div>{{ $candidate->candidate->display_name }}</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6  form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                                        <label for="email" class="control-label">@lang('site.email')</label>
                                        <div>{{ $candidate->email }}</div>
                                    </div>
                                    <div class="col-md-6  form-group {{ $errors->has('telephone') ? 'has-error' : ''}}">
                                        <label for="telephone" class="control-label">@lang('site.telephone')</label>
                                        <div>{{ $candidate->telephone }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6  form-group {{ $errors->has('gender') ? 'has-error' : ''}}">
                                        <label for="gender" class="control-label">@lang('site.gender')</label>
                                        <div>{{ gender($candidate->candidate->gender) }}</div>
                                    </div>
                                    <div class="col-md-6  form-group {{ $errors->has('date_of_birth') ? 'has-error' : ''}}">
                                        <label for="date_of_birth" class="control-label">@lang('site.date-of-birth') (DD/MM/YYYY)</label>
                                        <div>{{ \Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->day }}/{{ \Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->month }}/{{ \Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->year }} ({{  getAge(\Illuminate\Support\Carbon::parse($candidate->candidate->date_of_birth)->timestamp) }})</div>

                                    </div>
                                </div>


                                <div class="row">

                                    <div class="col-md-6  form-group">
                                        @if(!empty($candidate->candidate->picture))
                                            <label for="date_of_birth" class="control-label">@lang('site.picture')</label>

                                            <div><img   data-toggle="modal" data-target="#pictureModal" src="{{ asset($candidate->candidate->picture) }}"  class="int_w330cur" /></div> <br/>



                                            <div class="modal fade" id="pictureModal" tabindex="-1" role="dialog" aria-labelledby="pictureModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="pictureModalLabel">@lang('site.picture')</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body int_txcen"  >
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

                                    <div class="col-md-6  form-group">
                                        <label for="video_code">@lang('site.video')</label>
                                        @if(!empty($candidate->candidate->video_code))
                                            {!! clean( $candidate->candidate->video_code ) !!}
                                        @endif
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6  form-group {{ $errors->has('employed') ? 'has-error' : ''}}">
                                        <label for="employed" class="control-label">@lang('site.employed')</label>
                                        <div>{{ boolToString($candidate->candidate->employed) }}</div>
                                    </div>
                                    <div class="col-md-6  form-group {{ $errors->has('shortlisted') ? 'has-error' : ''}}">
                                        <label for="shortlisted" class="control-label">@lang('site.shortlisted')</label>
                                        <div>{{ boolToString($candidate->candidate->shortlisted) }}</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6  form-group {{ $errors->has('locked') ? 'has-error' : ''}}">
                                        <label for="locked" class="control-label">@lang('site.locked')</label>
                                        <div>{{ boolToString($candidate->candidate->locked) }}</div>

                                    </div>
                                    <div class="col-md-6  form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                                        <label for="status" class="control-label">@lang('site.enabled')</label>
                                        <div>{{ boolToString($candidate->candidate->status) }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6  form-group {{ $errors->has('public') ? 'has-error' : ''}}">
                                        <label for="public" class="control-label">@lang('site.public')</label>
                                        <div>{{ boolToString($candidate->candidate->public) }}</div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    @foreach(\App\CandidateFieldGroup::get() as $group)
                        <div class="card">
                            <div class="card-header" role="tab"  id="heading{{ $group->id }}">
                                <a class="collapsed" data-toggle="collapse" href="#collapse{{ $group->id }}" aria-expanded="true" aria-controls="collapse{{ $group->id }}">
                                    {{ $group->name }}
                                </a>
                            </div>
                            <div id="collapse{{ $group->id }}" class="collapse" aria-labelledby="heading{{ $group->id }}" role="tabpanel"  data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($group->candidateFields()->orderBy('sort_order')->get() as $field)
                                            <?php


                                            $value = ($candidate->candidateFields()->where('id',$field->id)->first()) ? $candidate->candidateFields()->where('id',$field->id)->first()->pivot->value:'';
                                            ?>

                                            @if($field->type=='text')
                                                <div class="col-md-6  form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                    <div>{{ $value }}</div>
                                                </div>
                                            @elseif($field->type=='select')
                                                <div class="col-md-6  form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                    <div>{{ $value }}</div>

                                                </div>
                                            @elseif($field->type=='textarea')
                                                <div class="col-md-6  form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                    <div>{{ $value }}</div>
                                                </div>
                                            @elseif($field->type=='checkbox')
                                                <div class="col-md-6  form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                    <div>{{ boolToString($value) }}</div>
                                                </div>

                                            @elseif($field->type=='radio')
                                                <div class="col-md-6  form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                    <div>{{ $value }}</div>

                                                </div>
                                            @elseif($field->type=='file')


                                                <div class="col-md-6  form-group">
                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>


                                                    @if(!empty($value))
                                                        <h3>{{ basename($value) }}</h3>
                                                        @if(isImage($value))
                                                            <div><img  data-toggle="modal" data-target="#pictureModal{{ $field->id }}" src="{{ route('employer.image') }}?file={{ $value }}"  class="int_w330cur" /></div> <br/>


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
                                                                            <img src="{{ route('employer.image') }}?file={{ $value }}" class="int_txcen" />
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                        @endif
                                                        <a class="btn btn-success" href="{{ route('employer.download') }}?file={{ $value }}"><i class="fa fa-download"></i> @lang('site.download')</a>
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

            <div id="tabCont4" class="tab-pane">

                <form action="{{ route('employer.placements.add-comment',['employment'=>$employment->id]) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="content">@lang('site.add-comment')</label>
                        <textarea autofocus required="required" class="form-control" name="content" id="content"></textarea>

                    </div>
                    <p class="help-block">@lang('site.placement-comment-hint')</p>

                    <div id="dropzone" class="dropmail int_tpmb" >

                        <div class="dropzone dropzone-custom needsclick" id="my-dropzone">
                            <div class="dz-message needsclick download-custom">
                                <i class="fa fa-download" aria-hidden="true"></i>
                                <h1>@lang('site.attachments')</h1>
                                <h2>@lang('site.upload-info')</h2>

                            </div>
                        </div>


                    </div>
                    <input type="hidden" name="msg_id" value="{{ $msgId }}"/>



                        <button type="submit" class="btn btn-primary">@lang('site.submit')</button>
                </form>

                <div id="comment-box" class="int_mt30px">

                </div>

            </div>

        </div><!-- card-body -->
    </div><!-- card -->





@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/placement.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/dropzone/dropzone.css') }}">
 @endsection

@section('footer')
    <script>
"use strict";
        $('#comment-box').load('{{ route('employer.placements.comments',['employment'=>$employment->id])  }}');

        $(document).on('click','.comment-links a',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#comment-box').text('@lang('site.loading')');
            $('#comment-box').load(url,function(){
                $('html, body').animate({
                    scrollTop: ($('#comment-box').offset().top)
                },500);
            });

        });
        @if(request()->has('comment'))

        $(function(){
            $('#commentTab').trigger('click');
            $('textarea#content').focus();
        });


        @endif


    </script>




    <!-- dropzone JS
		============================================ -->
    <script src="{{ asset('vendor/dropzone/dropzone.js') }}"></script>
    <script>
"use strict";

        Dropzone.autoDiscover = false;
        jQuery(document).ready(function() {

            $("div#my-dropzone").dropzone({
                url: "{{ route('employer.placement-comments.upload',['id'=>$msgId]) }}",
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.ppt,.pptx,.zip,.mp3,.mp4",
                maxFilesize: 20, // MB
                success: function (file, response) {
                    console.log("Sucesso");
                    console.log(response);
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                addRemoveLinks: true,
                removedfile: function(file) {
                    var name = file.name;
                    console.log(name);

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('employer.placement-comments.remove-upload',['id'=>$msgId]) }}',
                        data: {name: name,request: 2, _token:'{{ csrf_token() }}'},
                        sucess: function(data){
                            console.log('success: ' + data);
                        }
                    });
                    var _ref;
                    return (_ref = file.previewElement) != null? _ref.parentNode.removeChild(file.previewElement) : void 0;
                },
                dictRemoveFile: '@lang('site.remove-file')',
                dictCancelUpload: '@lang('site.cancel-upload')',
                dictCancelUploadConfirmation: '@lang('site.cancel-confirm')'
            });

        });

    </script>
@endsection


