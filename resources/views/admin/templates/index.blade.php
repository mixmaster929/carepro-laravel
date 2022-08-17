@extends('layouts.admin')


@section('pageTitle',__('site.templates'))

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @lang('site.active-template')
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ asset('templates/'.$currentTemplate->directory.'/preview.png') }}"  class="img-fluid rounded mx-auto d-block" />
                        </div>
                        <div class="col-md-6">
                            <h3>{{ $currentTemplate->name }}</h3>
                            <p>
                                @lang(tlang($currentTemplate->directory,'app-description'))
                            </p>
                            <!-- Default dropup button -->
                            <div class="btn-group dropup">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cogs"></i> @lang('site.customize')
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.templates.settings') }}">@lang('site.settings')</a>
                                    <a class="dropdown-item" href="{{ route('admin.templates.colors') }}">@lang('site.colors')</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="card">
        <div class="card-header">@lang('site.all-templates')</div>
        <div class="card-body">
            <div class="row">

                @foreach($templates as $template)
                    <div class="col-md-6 int_mb60"  >
                        <div class="row">
                            <div class="col-md-6">
                                <a href="#"  data-toggle="modal" data-target="#{{ $template }}Modal" ><img src="{{ asset('templates/'.$template.'/preview.png') }}"  class="img-fluid rounded mx-auto d-block" /></a>

                                <!-- Modal -->
                                <div class="modal fade" id="{{ $template }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $template }}ModalLabel" aria-hidden="true">
                                    <div class="modal-dialog  modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="{{ $template }}ModalLabel">{{ templateInfo($template)['name'] }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{ asset('templates/'.$template.'/preview.png') }}"  class="img-fluid" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3>{{ templateInfo($template)['name'] }}</h3>
                                <p>
                                    @lang(tlang($template,'app-description'))
                                </p>
                                <!-- Default dropup button -->
                                @if($currentTemplate->directory ==$template)
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> @lang('site.customize')
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.templates.settings') }}">@lang('site.settings')</a>
                                        <a class="dropdown-item" href="{{ route('admin.templates.colors') }}">@lang('site.colors')</a>
                                    </div>
                                </div>
                                    @else
                                    <a class="btn btn-primary" href="{{ route('admin.templates.install',['templateDir'=>$template]) }}"><i class="fa fa-tools"></i> @lang('site.install')</a>
                                @endif

                            </div>
                        </div>

                    </div>


                    @endforeach

            </div>


        </div>
    </div>


@endsection
