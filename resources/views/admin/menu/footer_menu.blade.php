@extends('layouts.admin-page')

@section('pageTitle',__('site.menus'))
@section('page-title',__('site.footer-menu'))

@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=>'#',
                'page'=>__('site.footer-menu')
            ],
    ]])
@endsection

@section('page-content')

    <div class="row">

        <div class="col-md-4">
            <h4>@lang('site.add-links')</h4>
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                @lang('site.pages')
                            </button>
                        </h2>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($pages as $page)

                                    <li class="list-group-item">{{ $page['name'] }}
                                        <form method="post" class="menuform int_inlinedisp" action="{{ route('admin.menus.save-footer') }}">
                                            @csrf
                                            <input type="hidden" name="name" value="{{ $page['name'] }}"/>
                                            <input type="hidden" name="label" value="{{ $page['name'] }}"/>
                                            <input type="hidden" name="url" value="{{ $page['url'] }}"/>
                                            <input type="hidden" name="type" value="p"/>
                                            <span onclick="$(this).parent().submit()"  class="int_curpoin badge badge-primary badge-pill float-right">@lang('site.add')</span>
                                        </form>
                                    </li>

                                @endforeach
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                @lang('site.articles')
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($articles as $page)

                                    <li class="list-group-item">{{ $page['name'] }}
                                        <form method="post" class="menuform int_inlinedisp"  action="{{ route('admin.menus.save-footer') }}">
                                            @csrf
                                            <input type="hidden" name="name" value="@lang('site.article'): {{ $page['name'] }}"/>
                                            <input type="hidden" name="label" value="{{ $page['name'] }}"/>
                                            <input type="hidden" name="url" value="{{ $page['url'] }}"/>
                                            <input type="hidden" name="type" value="a"/>
                                            <span onclick="$(this).parent().submit()"  class="int_curpoin badge badge-primary badge-pill float-right">@lang('site.add')</span>
                                        </form>
                                    </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                @lang('site.candidate-categories')
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($categories as $page)

                                    <li class="list-group-item">{{ $page['name'] }}
                                        <form method="post" class="menuform int_inlinedisp"  action="{{ route('admin.menus.save-footer') }}">
                                            @csrf
                                            <input type="hidden" name="name" value="@lang('site.category'): {{ $page['name'] }}"/>
                                            <input type="hidden" name="label" value="{{ $page['name'] }}"/>
                                            <input type="hidden" name="url" value="{{ $page['url'] }}"/>
                                            <input type="hidden" name="type" value="g"/>
                                            <span onclick="$(this).parent().submit()"   class="int_curpoin badge badge-primary badge-pill float-right">@lang('site.add')</span>
                                        </form>
                                    </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                @lang('site.job-categories')
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($jobCategories as $page)

                                    <li class="list-group-item">{{ $page['name'] }}
                                        <form method="post" class="menuform int_inlinedisp"   action="{{ route('admin.menus.save-header') }}">
                                            @csrf
                                            <input type="hidden" name="name" value="@lang('site.job-category'): {{ $page['name'] }}"/>
                                            <input type="hidden" name="label" value="{{ $page['name'] }}"/>
                                            <input type="hidden" name="url" value="{{ $page['url'] }}"/>
                                            <input type="hidden" name="type" value="v"/>
                                            <span onclick="$(this).parent().submit()"  class="int_curpoin badge badge-primary badge-pill float-right">@lang('site.add')</span>
                                        </form>
                                    </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="headingSeven">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                @lang('site.order-forms')
                            </button>
                        </h2>
                    </div>
                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($orderForms as $page)

                                    <li class="list-group-item">{{ $page['name'] }}
                                        <form method="post" class="menuform int_inlinedisp"   action="{{ route('admin.menus.save-footer') }}">
                                            @csrf
                                            <input type="hidden" name="name" value="@lang('site.order-form'): {{ $page['name'] }}"/>
                                            <input type="hidden" name="label" value="{{ $page['name'] }}"/>
                                            <input type="hidden" name="url" value="{{ $page['url'] }}"/>
                                            <input type="hidden" name="type" value="f"/>
                                            <span onclick="$(this).parent().submit()"  class="int_curpoin badge badge-primary badge-pill float-right">@lang('site.add')</span>
                                        </form>
                                    </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                @lang('site.custom')
                            </button>
                        </h2>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">

                            <form method="post" id="customForm" class="menuform resetform" action="{{ route('admin.menus.save-footer') }}">
                                @csrf
                                <input type="hidden" name="name" value="@lang('site.custom')" />
                                <input type="hidden" name="type" value="c"/>
                                <div class="form-group">
                                    <label for="label">@lang('site.label')</label>
                                    <input required class="form-control" type="text" name="label" value=""/>
                                </div>

                                <div class="form-group">
                                    <label for="url">URL</label>
                                    <input required  class="form-control" type="text" name="url" value=""/>
                                </div>


                                <div class="form-group">
                                    <label for="sort_order">@lang('site.sort-order')</label>
                                    <input class="form-control number" type="text" name="sort_order" value=""/>
                                </div>

                                <div class="form-group">
                                    <label for="parent_id">@lang('site.parent')</label>
                                    <select class="form-control" name="parent_id" id="parent_id">
                                        <option value="0">@lang('site.none')</option>
                                        @foreach(\App\FooterMenu::where('parent_id',0)->orderBy('sort_order')->get() as $option)
                                            <option value="{{ $option->id }}">{{ $option->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="new_window" type="checkbox" value="1" id="new_windowc">
                                    <label class="form-check-label" for="new_windowc">
                                        @lang('site.new-window')
                                    </label>
                                </div>
                                <br/>


                                <button class="btn btn-primary float-right">@lang('site.add')</button>

                            </form>


                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-8" >
            <h4>@lang('site.menu')</h4>
            <div>
                <img src="{{ asset('img/loader.gif') }}" id="loaderImg" class="int_hide"/>
            </div>
            <div id="menubox" class="accordion">

            </div>

        </div>


    </div>

@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.css') }}">
@endsection

@section('footer')
    <script src="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery/jquery.form.min.js') }}" type="text/javascript"></script>
    <script>
"use strict";

        function loadMenu(){
            $('#loaderImg').show();
            $('#menubox').load('{{ route('admin.menus.load-footer') }}',function(){
                $('#loaderImg').hide();
            });

        }

        loadMenu();

        $(document).on('submit','.menuform',function(e){
            e.preventDefault();
            $.toast('@lang('site.saving')');

            var formId = $(this).attr('id');
            $(this).ajaxSubmit({
                success: function(responseText, statusText, xhr, $form){
                    if(responseText.status){
                        $.toast('@lang('site.changes-saved')');
                        loadMenu();

                        if(formId=='customForm'){
                            document. getElementById("customForm").reset();
                        }
                    }
                    else{
                        $.toast(responseText.error);
                    }
                },
                error: function(jqXHR,textStatus,errorThrown){
                    $.toast('@lang('site.error-occurred')');
                }
            });
        });

        $(document).on('click','.deletemenu',function(e){
            e.preventDefault();
            $.toast('@lang('site.removing')');
            $.get($(this).attr('href'),function(data){
                loadMenu();
            });
        });




    </script>

@endsection
