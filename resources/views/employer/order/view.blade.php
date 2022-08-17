@extends($userLayout)

@section('page-title',__('site.order').' #'.$order->id)
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('employer.orders') }}">@lang('site.orders')</a></li>
    <li class="breadcrumb-item">@lang('site.view')</li>
@endsection


@section('content')

    <div class="card bd-0">
        <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
            <nav class="nav nav-tabs">
                <a class="nav-link active" data-toggle="tab" href="#tabCont1">@lang('site.order-details')</a>
                <a class="nav-link" data-toggle="tab" href="#tabCont2">@lang('site.shortlist')</a>
                <a class="nav-link" data-toggle="tab" href="#tabCont3">@lang('site.invoices')</a>
                <a id="commentTab" class="nav-link" data-toggle="tab" href="#tabCont4">@lang('site.comments') ({{ $order->orderComments()->count() }})</a>

            </nav>
        </div><!-- card-header -->
        <div class="card-body bd bd-t-0 tab-content">
            <div id="tabCont1" class="tab-pane active show">
                <div id="accordion2" class="accordion accordion-dark" role="tablist" aria-multiselectable="true">
                    <div class="card">
                        <div class="card-header" role="tab" id="headingOne2">
                            <a data-toggle="collapse" href="#collapseOne2" aria-expanded="false" aria-controls="collapseOne2">
                                @lang('site.general-details')
                            </a>
                        </div><!-- card-header -->

                        <div id="collapseOne2" data-parent="#accordion2" class="collapse show" role="tabpanel" aria-labelledby="headingOne2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 {{ $errors->has('user_id') ? 'has-error' : ''}}">
                                        <label for="user_id" class="control-label">@lang('site.id')</label>

                                        <div>{{ $order->id }}</div>

                                    </div>
                                    <div class="form-group col-md-6  ">
                                        <label for="added" class="control-label">@lang('site.added-on')</label>
                                        <div>{{ \Illuminate\Support\Carbon::parse($order->created_at)->format('d/M/Y') }}</div>


                                    </div>
                                </div>



                                <div class="row">


                                    <div class="form-group col-md-6 {{ $errors->has('status') ? 'has-error' : ''}}">
                                        <label for="status" class="control-label">@lang('site.status')</label>
                                        <div>{{ orderStatus($order->status) }}</div>

                                    </div>

                                    <div class="form-group col-md-6 {{ $errors->has('interview_date') ? 'has-error' : ''}}">
                                        <label for="interview_date" class="control-label">@lang('site.interview-date')</label>
                                        @if(!empty($order->interview_date))
                                            <div>{{ \Illuminate\Support\Carbon::parse($order->interview_date)->format('d/M/Y') }}</div>
                                        @endif

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>@lang('site.interview-location')</label>
                                        <div>{!! clean( nl2br(clean($order->interview_location)) ) !!}</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>@lang('site.interview-time')</label>
                                        <div>{{ $order->interview_time }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>@lang('site.comments')</label>
                                        <div>{!! clean( nl2br(clean($order->comments)) ) !!}</div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    @foreach(\App\OrderFieldGroup::where('order_form_id',$order->order_form_id)->orderBy('sort_order')->get() as $group)
                    <div class="card">
                        <div class="card-header" role="tab" id="heading{{ $group->id }}">
                            <a class="collapsed" data-toggle="collapse" href="#collapse{{ $group->id }}" aria-expanded="true" aria-controls="collapse{{ $group->id }}">
                                {{ $group->name }}
                            </a>
                        </div>
                        <div id="collapse{{ $group->id }}" class="collapse" data-parent="#accordion2" role="tabpanel" aria-labelledby="heading{{ $group->id }}">
                            <div class="card-body">
                                <div class="row">
                                    @foreach($group->orderFields()->orderBy('sort_order')->get() as $field)
                                        <?php


                                        $value = ($order->orderFields()->where('id',$field->id)->first()) ? $order->orderFields()->where('id',$field->id)->first()->pivot->value:'';
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


                </div><!-- accordion -->

            </div><!-- tab-pane -->
            <div id="tabCont2" class="tab-pane">

                <div class="row">
                    @foreach($order->candidates as $item)

                        <div class="card col-md-3 bd-0 rounded">

                            @if(!empty($item->picture) && file_exists($item->picture))
                                <img  class="img-fluid"   src="{{ asset($item->picture) }}">
                            @elseif($item->gender=='m')
                                <img  class="img-fluid"   src="{{ asset('img/man.jpg') }}">
                            @else
                                <img  class="img-fluid"   src="{{ asset('img/woman.jpg') }}">
                            @endif
                            <div class="card-body bd bd-t-0">
                                <h5 class="card-title">{{ $item->display_name }}</h5>
                                <p class="card-text">
                                    <strong>@lang('site.age'):</strong> {{ getAge(\Illuminate\Support\Carbon::parse($item->date_of_birth)->timestamp) }}
                                    <br/>
                                    <strong>@lang('site.gender'):</strong> {{ gender($item->gender) }}
                                </p>
                                <a target="_blank" href="{{ route('profile',['candidate'=>$item->id]) }}" class="card-link  btn btn-sm btn-primary rounded">@lang('site.view-profile')</a>
                            </div>
                        </div><!-- card -->


                    @endforeach
                </div>


            </div>
            <div id="tabCont3" class="tab-pane">

                <div class="table-responsive int_mt10"  >
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('site.item')</th>
                            <th>@lang('site.amount')</th>
                            <th>@lang('site.status')</th>
                            <th>@lang('site.created-on')</th>
                            <th>@lang('site.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->invoices()->latest()->get() as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->title }} </td>
                                <td>{!! clean( check( price($item->amount)) ) !!}</td>
                                <td>{{ ($item->paid==1)? __('site.paid'):__('site.unpaid') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/M/Y') }}
                                </td>
                                <td>



                                    @if($item->paid==0)
                                        <a class="btn btn-success" href="{{ route('user.billing.pay',['invoice'=>$item->id]) }}"><i class="fa fa-money-check"></i> @lang('site.pay-now')</a>  &nbsp;
                                    @endif
                                    <a class="btn btn-primary" href="{{ route('user.billing.invoice',['invoice'=>$item->id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>


                                </td>
                            </tr>


                        @endforeach
                        </tbody>
                    </table>
                </div>


            </div>

            <div id="tabCont4" class="tab-pane">

                <form action="{{ route('employer.orders.add-comment',['order'=>$order->id]) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="content">@lang('site.add-comment')</label>
                        <textarea autofocus required="required" class="form-control" name="content" id="content"></textarea>
                    </div>
                        <button type="submit" class="btn btn-primary">@lang('site.submit')</button>
                </form>

                <div id="comment-box" class="int_mt30px">

                </div>

            </div>

        </div><!-- card-body -->
    </div><!-- card -->





@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/boldheader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/boldlabel.css') }}">
    @endsection

@section('footer')
    <script>
"use strict";
        $('#comment-box').load('{{ route('employer.orders.comments',['order'=>$order->id])  }}');

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


    @endsection
