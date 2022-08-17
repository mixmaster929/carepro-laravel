@extends($userLayout)

@section('page-title',__('site.placements'))

@section('content')

    @include('candidate.home.placement-list',['employments'=>$employments,'perPage'=>$perPage])
    {{ $employments->links() }}

@endsection