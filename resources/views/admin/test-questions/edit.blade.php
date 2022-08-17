@extends('layouts.admin-page')

@section('pageTitle',__('site.manage-questions'))
@section('page-title',__('site.edit').' '.__('site.question').' #'.$testquestion->id)
@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=> route('admin.tests.index'),
                'page'=>__('site.tests')
            ],
            [
                'link'=>route('admin.test-questions.index',['test'=>$testquestion->test_id]),
                'page'=>__('site.manage-questions')
            ],
            [
                'link'=>'#',
                'page'=>__('site.edit').' '.__('site.question')
            ]
    ]])
@endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        @can('access','view_test_questions')
                        <a href="{{ route('admin.test-questions.index',['test'=>$testquestion->test_id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        <br />
                        <br />



                        <form method="POST" action="{{ url('/admin/test-questions/' . $testquestion->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('admin.test-questions.form', ['formMode' => 'edit'])

                            <br/>
                            <a  data-toggle="modal" data-target="#exampleModal" class="btn btn-primary float-right btn-sm" href="#"><i class="fa fa-plus"></i> @lang('site.add-options')</a>
                            <h3>@lang('site.options')</h3>

                            <table class="table">
                                <thead>
                                <tr>
                                    <th>@lang('site.option')</th>
                                    <th>@lang('site.correct-answer')</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($testquestion->testOptions as $option)
                                    <tr>
                                        <td>{{ $option->option }}</td>
                                        <td >
                                         @if($option->is_correct==1)
                                        <i class="fa fa-check"></i>
                                             @endif

                                        </td>
                                        <td>
                                            <a onclick="return confirm('@lang('site.confirm-delete')')" class="btn btn-sm btn-primary" href="{{ route('admin.test-options.delete',['testOption'=>$option->id]) }}"><i class="fa fa-trash"></i>
                                                @lang('site.delete')
                                            </a>

                                            <a data-href="{{ route('admin.test-options.edit',['testOption'=>$option->id]) }}" class="btn btn-success btn-sm editbtn" href="#"  data-toggle="modal" data-target="#editModal" >
                                                <i class="fa fa-edit"></i>
                                                @lang('site.edit')
                                            </a>


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="{{ __('site.update') }}">
                            </div>
                        </form>

                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">@lang('site.edit-option')</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="edit-content">
                                        ...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('admin.test-options.store',['testQuestion'=>$testquestion->id]) }}" method="post">
                                    @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">@lang('site.add-options')</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
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
                                                    <td class="int_pdt30"><input    type="radio" name="correct_option" value="{{ $i }}"/></td>

                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('site.close')</button>
                                        <button type="submit" class="btn btn-primary">@lang('site.save-changes')</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>

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

    <script>
"use strict";

        $('.editbtn').on('click',function(){
            var url = $(this).attr('data-href');
            $('#edit-content').text('@lang('site.loading')');
            $('#edit-content').load(url);
        });

    </script>
@endsection
