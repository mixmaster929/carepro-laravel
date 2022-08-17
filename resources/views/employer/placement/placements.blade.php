@extends($userLayout)

@section('page-title',__('site.my-placements'))
@section('content')

    @include('employer.placement.placement-list',compact('employments'))
    <div class="pagination-wrapper"> {!! clean( $employments->appends(request()->input())->render() ) !!} </div>


@endsection
