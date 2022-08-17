@extends('layouts.admin-page')

@section('pageTitle',__('site.test-results').': '.$test->name)
@section('page-title',$userTest->user->name)
@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=> route('admin.tests.index'),
                'page'=>__('site.tests')
            ],
            [
                'link'=>route('admin.tests.attempts',['test'=>$test->id]),
                'page'=>__('site.test-attempts')
            ],
            [
                'link'=>'#',
                'page'=>__('site.test-results')
            ],
    ]])
@endsection
@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ route('admin.tests.attempts',['test'=>$userTest->test_id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>


                        <br/>
                        <br/>

                        <ul class="list-group">
                            @foreach($userTest->userTestOptions as $option)
                            <li class="list-group-item">
                                <h3>@lang('site.question')</h3>
                                {!! clean( $option->testOption->testQuestion->question ) !!}</li>
                            <li class="list-group-item active"><strong>@lang('site.answer')</strong>: {{ $option->testOption->option }}</li>
                            @endforeach

                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
