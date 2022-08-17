@extends($userLayout)

@section('page-title',__('site.applications'))

@section('content')

    @include('candidate.home.application-list',['applications'=>$applications,'perPage'=>$perPage])
    {{ $applications->links() }}

@endsection