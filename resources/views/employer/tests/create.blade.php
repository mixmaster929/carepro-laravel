@extends($userLayout)

@section('pageTitle',__('site.tests'))
@section('page-title',__('site.create-new').' '.__('site.test'))

    @section('breadcrumb')
        @include('partials.breadcrumb',['crumbs'=>[
                [
                    'link'=> route('employer.tests.index'),
                    'page'=>__('site.tests')
                ],
                [
                    'link'=>'#',
                    'page'=>__('site.create-test')
                ]
        ]])
    @endsection

@section('content')
    <div>
        <a href="{{ url('/employer/tests') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
        <br />
        <br />
        <form method="POST" action="{{ url('/employer/tests') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include ('employer.tests.form', ['formMode' => 'create'])
        </form>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
@endsection

@section('footer')
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('js/admin/ofg-edit.js') }}"></script>
@endsection
