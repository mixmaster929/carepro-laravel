@extends($userLayout)

@section('page-title',__('site.tests'))

@section('content')

    @include('candidate.test.test-list',['tests'=>$tests])
    {{ $tests->links() }}
@endsection