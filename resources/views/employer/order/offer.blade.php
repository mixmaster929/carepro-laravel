@extends($userLayout)

@section('pageTitle',__('site.bids').': '.$order->title)
@section('page-title',__('site.bids').' ('.$order->bids->count().')'.': '.$order->title)

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
            <a href="{{ url('/employer/orders') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
            <br/>
            <br/>
            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('site.picture')</th>
                            <th>@lang('site.candidate')</th>
                            <th>@lang('site.location')</th>
                            <th>@lang('site.age')</th>
                            <th>@lang('site.shortlisted')</th>
                            <th>@lang('site.added-on')</th>
			                <th>@lang('site.bids')</th>
                            <th>@lang('site.status')</th>
                            <th>@lang('site.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($offers as $item)
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
                        $candidate = App\User::find($item->user_id);
                        // $pivotData = $order->bids()->wherePivot('user_id', Auth::user()->id)->first();
                        $location = $candidate->candidateFields()->where('name','Plaats')->first()? $candidate->candidateFields()->where('name','Plaats')->first()->pivot->value : "";
                    ?>
                        <tr>
                            <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                            <td>
                                <div class="media align-items-center">
                                    <span class="avatar avatar-sm rounded-circle">
                                        @if(!empty(\App\User::find($item->user_id)->candidate->picture) && file_exists(\App\User::find($item->user_id)->candidate->picture))
                                            <img   src="{{ asset($item->user->candidate->picture) }}">
                                        @else
                                            <img   src="{{ asset('img/man.jpg') }}">
                                        @endif

                                    </span>
                                </div>
                            </td>
                            <td>{{ \App\User::find($item->user_id)->name }}</td>
                            <td>{{ $location }}</td>
                            <td>{{ getAge(\Illuminate\Support\Carbon::parse(\App\User::find($item->user_id)->candidate->date_of_birth)->timestamp) }}</td>
                            <td>{{ boolToString($item->shortlisted) }}</td>
			                <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
                            <td>@if($status === 'site.allow') <span class='badge badge-success'>@else <span>@endif {{'€'.$item->offer}}</span></td>
                            <td>@lang($status)</td>
                            <td>
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ni ni-settings"></i> @lang('site.actions')
                                    </button>
                                    <div class="dropdown-menu">
                                        <!-- Dropdown menu links -->
                                        <a style="pointer-events: {{$status === 'site.allow'? 'none' : 'show'}};" class="dropdown-item" href="#" onclick="$('#allowForm{{ $item->id }}').submit()">@lang('site.allow')</a>
                                        <a style="pointer-events: {{$status === 'site.allow'? 'none' : 'show'}};" class="dropdown-item" href="#" onclick="$('#denyForm{{ $item->id }}').submit()">@lang('site.deny')</a>
                                        <a class="dropdown-item" href="{{ route('employer.offer.create-interview', ['order' => $item->order_id, 'user' => $item->user_id]) }}">@lang('site.make-interview')</a>
                                        <a class="dropdown-item" href="{{ route('profile',['candidate' => $candidate->candidate->id]) }}">@lang('site.view-candidate')</a>
                                        <a class="dropdown-item" href="{{ route('employer.order.create-placement', ['order' => $item->order_id, 'user'=>$item->user_id]) }}">@lang('site.make-placement')</a>
                                        <a class="dropdown-item" href="{{ route('employer.order.shortlist',['order'=>$item->order_id, 'user'=>$item->user_id]) }}?status={{ $item->shortlisted==1? 0:1 }}">{{ $item->shortlisted==1? __('site.remove-from').' ':'' }}@lang('site.shortlist')</a>
                                        <a class="dropdown-item" href="{{ route('employer.offers.comments',['id'=>$item->id]) }}" >@lang('site.comment')</a>
                                        <a class="dropdown-item" href="{{ route('employer.candidates.tests',['user'=>$item->user_id]) }}" >@lang('site.test-results') ({{ \App\User::find($item->user_id)->userTests()->count() }})</a>
                                    </div>
                                </div>
                                <form  onsubmit="return confirm(&quot; @lang('site.allow') &quot;)"   id="allowForm{{ $item->id }}"  method="POST" action="{{ url('/employer/offers/allow' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}
                                </form>
                                <form  onsubmit="return confirm(&quot; @lang('site.deny') &quot;)"   id="denyForm{{ $item->id }}"  method="POST" action="{{ url('/employer/offers/deny' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper"></div>
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
