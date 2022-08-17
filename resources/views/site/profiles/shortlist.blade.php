@extends($userLayout)

@section('page-title',__('site.your-shortlist'))
@section('inline-title',__('site.your-shortlist'))

@section('crumb')
    <li>{{ __('site.your-shortlist') }}</li>
@endsection

@section('content')

    <section class="about-area them-2 pb-130 pt-50">



                    <div class="about-content them-2 int_pt10"  >
                        <!-- az-dashboard-one-title -->
                        @if(Session::has('candidate') && \App\Candidate::find(session()->get('candidate')))
                            <br/>
                            <p class="alert alert-success">
                                @lang('site.shortlist-add-msg',['name'=>\App\Candidate::find(session()->get('candidate'))->display_name])

                                <a class="btn btn-primary rounded" href="{{ route('profiles') }}">@lang('site.browse-more-profiles')</a>
                                <a class="btn btn-success rounded" href="{{ route('order-forms') }}?shortlist">@lang('site.complete-order-form')</a>
                            </p>

                        @endif

                        @if(!is_array($cart) || count($cart)==0)
                            @lang('site.empty-shortlist') <br/>
                            <a class="btn btn-primary rounded" href="{{ route('profiles') }}">@lang('site.browse-profiles')</a>
                        @else
                            <div class="container-fluid">
                                <div class="row" style="width: 100%;" >

                                    @foreach($cart as $item)

                                        <?php
                                        $candidate = \App\Candidate::find($item);
                                        ?>


                                        <div class="card col-md-4 bd-0 rounded mb-3">

                                            @if(!empty($candidate->picture) && file_exists($candidate->picture))
                                                <img  class="img-fluid"   src="{{ asset($candidate->picture) }}">
                                            @elseif($candidate->gender=='m')
                                                <img  class="img-fluid"   src="{{ asset('img/man.jpg') }}">
                                            @else
                                                <img  class="img-fluid"   src="{{ asset('img/woman.jpg') }}">
                                            @endif
                                            <div class="card-body bd bd-t-0">
                                                <h5 class="card-title">{{ $candidate->display_name }}</h5>
                                                <p class="card-text">
                                                    <strong>@lang('site.age'):</strong> {{ getAge(\Illuminate\Support\Carbon::parse($candidate->date_of_birth)->timestamp) }}
                                                    <br/>
                                                    <strong>@lang('site.gender'):</strong> {{ gender($candidate->gender) }}
                                                </p>
                                                <a  href="{{ route('profile',['candidate'=>$candidate->id]) }}" class="card-link  btn btn-sm btn-primary rounded">@lang('site.view-profile')</a>

                                                <a onclick="return confirm('@lang('site.confirm-delete')')" href="{{ route('shortlist.remove',['candidate'=>$candidate->id]) }}" class="btn btn-sm  btn-danger rounded"><i class="fa fa-trash"></i> @lang('site.remove')</a>

                                            </div>
                                        </div><!-- card -->

                                        @if(false)
                                            <div class="card int_wi18re"  >
                                                <a href="{{ route('profile',['candidate'=>$candidate->id]) }}">

                                                    @if(!empty($candidate->picture) && file_exists($candidate->picture))
                                                        <img class="card-img-top"  src="{{ asset($candidate->picture) }}" >
                                                    @elseif($candidate->gender=='m')
                                                        <img  class="card-img-top"    src="{{ asset('img/man.jpg') }}">
                                                    @else
                                                        <img  class="card-img-top"   src="{{ asset('img/woman.jpg') }}">
                                                    @endif
                                                </a>
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $candidate->display_name }}</h5>
                                                    <p class="card-text">{{ getAge(\Illuminate\Support\Carbon::parse($candidate->date_of_birth)->timestamp) }}/{{ gender($candidate->gender) }}</p>
                                                    <a onclick="return confirm('@lang('site.confirm-delete')')" href="{{ route('shortlist.remove',['candidate'=>$candidate->id]) }}" class="btn btn-sm  btn-danger rounded"><i class="fa fa-trash"></i> @lang('site.remove')</a>

                                                </div>
                                            </div>
                                        @endif







                                    @endforeach
                                </div>

                            </div>

                            <div>
                                <hr/>
                                <a   class="int_mb10 btn btn-primary rounded" href="{{ route('profiles') }}">@lang('site.browse-more-profiles')</a>
                                <a class="btn btn-success rounded float-right" href="{{ route('order-forms') }}?shortlist">@lang('site.complete-order-form')</a>
                            </div>


                        @endif



                    </div> <!-- about content -->

    </section>





















@endsection
