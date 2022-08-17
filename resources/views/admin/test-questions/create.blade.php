@extends('layouts.admin-page')

@section('pageTitle',__('site.manage-questions'))
@section('page-title',__('site.create-new').' '.__('site.question'))

@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=> route('admin.tests.index'),
                'page'=>__('site.tests')
            ],
            [
                'link'=>route('admin.test-questions.index',['test'=>$test->id]),
                'page'=>__('site.manage-questions')
            ],
            [
                'link'=>'#',
                'page'=>__('site.create-new').' '.__('site.question')
            ]
    ]])
@endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="{{ route('admin.test-questions.index',['test'=>$test->id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />


                        <form method="POST" action="{{ route('admin.test-questions.store',['test'=>$test->id]) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.test-questions.form', ['formMode' => 'create'])

                            <br/>
                            <h3>@lang('site.options')</h3>
                            <p><small>@lang('site.options-note')</small></p>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>@lang('site.option')</th>
                                        <th>@lang('site.correct-answer')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i=1;$i<=5;$i++)
                                        <tr>
                                            <td><input name="option_{{ $i }}" value="{{ old('option_'.$i) }}" class="form-control" type="text"/></td>
                                            <td class="int_pdt30"><input  required="required"  type="radio" name="correct_option" value="{{ $i }}"/></td>
                                        </tr>
                                        @endfor
                                </tbody>
                            </table>


                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="{{  __('site.create') }}">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
@endsection

@section('footer')
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('js/admin/questionte.js') }}"></script>

@endsection
