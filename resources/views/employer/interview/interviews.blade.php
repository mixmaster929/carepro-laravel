@extends($userLayout)

@section('page-title',__('site.interviews'))
@section('content')
@include('employer.interview.interview-list',['interviews'=>$interviews])
    <br/>
    {{ $interviews->links() }}
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/interview.css') }}">

@endsection
