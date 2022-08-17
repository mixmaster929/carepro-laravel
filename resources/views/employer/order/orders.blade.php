@extends($userLayout)

@section('page-title',__('site.my-orders'))
@section('content')


       @include('employer.order.order-list',['orders'=>$orders])

    <div class="pagination-wrapper"> {!! clean( $orders->appends(request()->input())->render() ) !!} </div>

@endsection
