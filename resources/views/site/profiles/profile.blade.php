@extends($userLayout)

@section('page-title',__('site.profile').': '.$candidate->display_name)
@section('inline-title',__('site.profiles'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('profiles') }}">@lang('site.profiles')</a></li>
    <li class="breadcrumb-item">@lang('site.view-profile')</li>

@endsection
@section('content')



    <div class="az-content az-content-profile">
        <div class="container mn-ht-100p">
            <div class="az-content-left az-content-left-profile">

                <div class="az-profile-overview">
                    <div class="az-img-user_ ">
                        @if(!empty($candidate->picture) && file_exists($candidate->picture))
                            <img   class=" img-fluid rounded mx-auto d-block "  src="{{ asset($candidate->picture) }}">
                        @elseif($candidate->gender=='m')
                            <img   class=" img-fluid rounded mx-auto d-block "    src="{{ asset('img/man.jpg') }}">
                        @else
                            <img    class=" img-fluid rounded mx-auto d-block "   src="{{ asset('img/woman.jpg') }}">
                        @endif
                    </div><!-- az-img-user -->
                    <div class="d-flex justify-content-between mg-b-20 pt-2">
                        <div>
                            <h5 class="az-profile-name">{{ $candidate->display_name }}</h5>
                            <p class="az-profile-name-text">@lang('site.age'): {{ getAge(\Illuminate\Support\Carbon::parse($candidate->date_of_birth)->timestamp) }}</p>

                        </div>

                    </div>


                    <hr class="mg-y-30">

                    <div class="az-profile-bio">
                        <a href="{{ route('shortlist-candidate',['candidate'=>$candidate->id]) }}" class="btn btn-primary btn-block btn-lg rounded"><i class="fa fa-user-plus"></i> @lang('site.shortlist')</a>

                    </div><!-- az-profile-bio -->

                </div><!-- az-profile-overview -->

            </div><!-- az-content-left -->
            <div class="az-content-body az-content-body-profile">
                <nav class="nav az-nav-line"  role="tablist">
                    <a  class="nav-link active" data-toggle="tab"  id="home-tab"  href="#home"  >@lang('site.details')</a>
                    <a   class="nav-link"  id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" >@lang('site.video')</a>

                </nav>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="az-profile-body">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="az-content-label tx-13 mg-b-5">@lang('site.age')</div>
                            <div class="az-profile-work-list">
                                <div class="media">
                                     <div class="media-body">
                                        <p>{{ getAge(\Illuminate\Support\Carbon::parse($candidate->date_of_birth)->timestamp) }}</p>

                                    </div><!-- media-body -->
                                </div>
                            </div><!-- az-profile-work-list -->
                        </div>
                    </div><!-- row -->

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="az-content-label tx-13 mg-b-5">@lang('site.gender')</div>
                            <div class="az-profile-work-list">
                                <div class="media">
                                    <div class="media-body">
                                        <p>{{ gender($candidate->gender) }}</p>
                                    </div><!-- media-body -->
                                </div>
                            </div><!-- az-profile-work-list -->
                        </div>
                    </div><!-- row -->


                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="az-content-label tx-13 mg-b-5">@lang('site.date-of-birth')</div>
                            <div class="az-profile-work-list">
                                <div class="media">
                                    <div class="media-body">
                                        <p>{{ \Illuminate\Support\Carbon::parse($candidate->date_of_birth)->format('F Y') }} ({{ getAge(\Illuminate\Support\Carbon::parse($candidate->date_of_birth)->timestamp) }} @lang('site.years-old'))</p>

                                    </div><!-- media-body -->
                                </div>
                            </div><!-- az-profile-work-list -->
                        </div>
                    </div><!-- row -->


                    @if($candidate->categories()->exists())
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="az-content-label tx-13 mg-b-5">@lang('site.categories')</div>
                            <div class="az-profile-work-list">
                                <div class="media">
                                    <div class="media-body">
                                        @foreach($candidate->categories as $category)
                                            <p>{{ $category->name }}</p>
                                        @endforeach

                                    </div><!-- media-body -->
                                </div>
                            </div><!-- az-profile-work-list -->
                        </div>
                    </div><!-- row -->
                    @endif
                    @foreach($groups as $group)
                    <h3>{{ $group->name }}</h3>
                        @foreach($group->candidateFields()->orderBy('sort_order')->get() as $field)

                            <?php
                            $value = ($candidate->user->candidateFields()->where('id',$field->id)->first()) ? $candidate->user->candidateFields()->where('id',$field->id)->first()->pivot->value:'';
                            ?>
                            @if($field->type != 'file' && $value != '')
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="az-content-label tx-13 mg-b-5">{{ $field->name }}</div>
                            <div class="az-profile-work-list">
                                <div class="media">
                                    <div class="media-body">
                                        <p>
                                            @if($field->type=='checkbox')
                                                {{ boolToString($value) }}
                                            @elseif($field->type=='text' || $field->type=='textarea' || $field->type=='select' || $field->type=='radio')
                                                {{ $value }}
                                            @endif

                                        </p>

                                    </div><!-- media-body -->
                                </div>
                            </div><!-- az-profile-work-list -->
                        </div>
                    </div><!-- row -->
                        @endif
                        @endforeach

                    @endforeach



                    <div class="mg-b-20"></div>

                </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="text-center ml-4 mt-4 " >
                        @if(!empty($candidate->video_code))
                            <!-- Start Single Section -->
                                <div class="single-section">
                                    <h4>@lang('site.video')</h4>
                                    <div>
                                        {!! $candidate->video_code  !!}
                                    </div>
                                </div>
                                <!-- End Single Section -->
                            @endif
                        </div>
                    </div>


                </div>
                <!-- az-profile-body -->
            </div><!-- az-content-body -->
        </div><!-- container -->
    </div><!-- az-content -->




@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/templates/busprofile.css') }}">
    <style>
        .az-profile-work-list .media-body {
            margin-left: 0px;
        }
    </style>
    @endsection
