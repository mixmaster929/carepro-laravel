@extends($userLayout)
<?php 
    $pivotData = $order->bids()->wherePivot('user_id', $user->id)->first();
    $offer = $pivotData && $pivotData->pivot->offer? $pivotData->pivot->offer : '';
    $offer1 = $pivotData && $pivotData->pivot->offer? ' €'.$pivotData->pivot->offer : '';
?>
@section('page-title',__('site.bid').' '.$offer1.': '.\App\User::find($user->id)->name)

@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('employer.view-bids', ['order' => $order->id]) }}">@lang('site.orders')</a></li>
    <li class="breadcrumb-item">@lang('site.view')</li>
@endsection


@section('content')

    <div class="card bd-0">
        <div class="card-body bd bd-t-0 tab-content">
            <form action="{{ route('employer.orders.add-comment',['order'=>$order->id]) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="content">@lang('site.add-comment')</label>
                    <textarea autofocus required="required" class="form-control" name="content" id="content"></textarea>
                    <input type="hidden" name="candidate_id" id="candidate_id" value="{{$user->id}}" />
                </div>
                <button type="submit" class="btn btn-primary">@lang('site.submit')</button>
            </form>

            <div id="comment-box" class="int_mt30px">

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
        $('#comment-box').load('{{ route('employer.orders.comments',['order'=>$order->id, 'user'=>$user->id])  }}');

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
