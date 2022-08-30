@extends($userLayout)

@section('page-title',__('site.interviews'))
@section('content')
@include('candidate.interview.interview-list',['interviews'=>$interviews])
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/interview.css') }}">

@endsection
