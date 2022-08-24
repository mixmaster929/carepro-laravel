<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Caremate">
    <title>@yield('pageTitle',__('site.admin'))</title>
    <!-- Favicon -->
    @if(!empty(setting('image_icon')))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset(setting('image_icon')) }}">
        @endif
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('themes/argonpro/assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('themes/argonpro/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
    <!-- Page plugins -->
    <!-- Argon CSS -->
        <link href="{{ asset('themes/argon/assets/js/plugins/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('themes/argonpro/assets/css/argon.min.css?v=1.1.0') }}" type="text/css">

        <link href="{{ asset('css/admin.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/fix.css') }}" rel="stylesheet" />

        @yield('header')
</head>

<body>
<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner_ scrollerbox">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ url('/') }}">
                @if(!empty(setting('image_logo')))
                    <img src="{{ asset(setting('image_logo')) }}" class="navbar-brand-img" >
                @else
                    <h1>{{ setting('general_site_name') }}</h1>
                @endif
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item" >
                        <a class=" nav-link" href="{{ route('admin.dashboard') }}"> <i class="ni ni-tv-2 text-primary"></i>   <span class="nav-link-text">@lang('amenu.dashboard')</span>
                        </a>
                    </li>

                    @can('access-group','orders')
                    <li class="nav-item">
                        <a class="nav-link" href="#nav-orders" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="orders">
                            <i class="ni ni-delivery-fast text-orange"></i>
                            <span class="nav-link-text">@lang('amenu.orders')</span>
                        </a>
                        <div class="collapse" id="nav-orders">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('/admin/orders') }}?create" class="nav-link">@lang('amenu.create-order')</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('/admin/orders') }}" class="nav-link">@lang('amenu.all-orders')</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('/admin/orders') }}?status=p" class="nav-link">@lang('amenu.pending-orders')</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('/admin/orders') }}?status=i" class="nav-link">@lang('amenu.in-progress-orders')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/admin/orders') }}?status=c" class="nav-link">@lang('amenu.completed-orders')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/admin/orders') }}?status=x" class="nav-link">@lang('amenu.cancelled-orders')</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('access-group','employers')
                    <li class="nav-item">
                        <a class="nav-link" href="#nav-employers" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-employers">
                            <i class="ni ni-circle-08 text-yellow"></i>
                            <span class="nav-link-text">@lang('amenu.employers')</span>
                        </a>
                        <div class="collapse" id="nav-employers">
                            <ul class="nav nav-sm flex-column">
                                @can('access','create_employer')
                                <li class="nav-item">
                                    <a href="{{ route('admin.employers.create') }}" class="nav-link">@lang('amenu.create-employer')</a>
                                </li>
                                @endcan

                                @can('access','view_employers')
                                <li class="nav-item">
                                    <a href="{{ route('admin.employers.index') }}" class="nav-link">@lang('amenu.all-employers')</a>
                                </li>
                                @endcan
                                @can('access','view_employers')
                                <li class="nav-item">
                                    <a href="{{ route('admin.employers.index') }}?active=1" class="nav-link">@lang('amenu.active-employers')</a>
                                </li>
                                @endcan
                                @can('access','view_employers')
                                <li class="nav-item">
                                    <a href="{{ route('admin.employers.index') }}?active=0" class="nav-link">@lang('amenu.inactive-employers')</a>
                                </li>
                                @endcan
                                @can('access','create_employer')
                                <li class="nav-item">
                                    <a href="{{ route('admin.employers.import-1') }}" class="nav-link">@lang('site.import')</a>
                                </li>
                                @endcan


                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('access-group','candidates')
                    <li class="nav-item">
                        <a class="nav-link" href="#nav-candidates" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-candidates">
                            <i class="ni ni-single-02 text-blue"></i>
                            <span class="nav-link-text">@lang('amenu.candidates')</span>
                        </a>
                        <div class="collapse" id="nav-candidates">
                            <ul class="nav nav-sm flex-column">
                                @can('access','create_candidate')
                                <li class="nav-item">
                                    <a href="{{ route('admin.candidates.create') }}" class="nav-link">@lang('amenu.create-candidate')</a>
                                </li>
                                @endcan

                                @can('access','view_candidates')
                                <li class="nav-item">
                                    <a href="{{ route('admin.candidates.index') }}" class="nav-link">@lang('amenu.all-candidates')</a>
                                </li>
                                @endcan
                                @can('access','view_candidates')
                                <li class="nav-item">
                                    <a href="{{ route('admin.candidates.index') }}?shortlisted=1" class="nav-link">@lang('amenu.shortlisted-candidates')</a>
                                </li>
                                @endcan
                                @can('access','view_candidates')
                                <li class="nav-item">
                                    <a href="{{ route('admin.candidates.index') }}?employed=1" class="nav-link">@lang('amenu.employed-candidates')</a>
                                </li>
                                @endcan
                                @can('access','view_candidates')
                                <li class="nav-item">
                                    <a href="{{ route('admin.candidates.index') }}?public=1" class="nav-link">@lang('amenu.public-candidates')</a>
                                </li>
                                @endcan

                                @can('access','view_categories')
                                <li class="nav-item">
                                    <a href="{{ route('admin.categories.index') }}" class="nav-link">@lang('amenu.categories')</a>
                                </li>
                                @endcan

                                @can('access','create_candidate')
                                <li class="nav-item">
                                    <a href="{{ route('admin.candidates.import-1') }}" class="nav-link">@lang('site.import')</a>
                                </li>
                                @endcan


                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('access','view_employments')
                    <li class="nav-item" >
                        <a class=" nav-link" href="{{ route('admin.employments.browse') }}"> <i class="fa fa-user-friends text-primary"></i>   <span class="nav-link-text">@lang('amenu.employments')</span>
                        </a>
                    </li>
                    @endcan

                    @can('access-group','interviews')
                    <li class="nav-item">
                        <a class="nav-link" href="#nav-interviews" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-invoices">
                            <i class="ni ni-calendar-grid-58 text-orange"></i>
                            <span class="nav-link-text">@lang('site.interviews')</span>
                        </a>
                        <div class="collapse" id="nav-interviews">
                            <ul class="nav nav-sm flex-column">
                                @can('access','create_interview')
                                <li class="nav-item">
                                    <a href="{{ url('/admin/interviews/create') }}" class="nav-link">@lang('site.create-interview')</a>
                                </li>
                                @endcan

                                @can('access','view_interviews')
                                <li class="nav-item">
                                    <a href="{{ url('/admin/interviews') }}" class="nav-link">@lang('site.all-interviews')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/admin/interviews') }}?type=u" class="nav-link">@lang('site.upcoming-interviews')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/admin/interviews') }}?type=p" class="nav-link">@lang('site.past-interviews')</a>
                                </li>

                                @endcan



                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('access-group','invoices')
                    <li class="nav-item">
                        <a class="nav-link" href="#nav-invoices" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-invoices">
                            <i class="ni ni-money-coins text-cyan"></i>
                            <span class="nav-link-text">@lang('amenu.invoices')</span>
                        </a>
                        <div class="collapse" id="nav-invoices">
                            <ul class="nav nav-sm flex-column">
                                @can('access','view_invoices')
                                <li class="nav-item">
                                    <a href="{{ url('/admin/invoices') }}" class="nav-link">@lang('site.view-invoices')</a>
                                </li>
                                @endcan

                                @can('access','create_invoice')
                                <li class="nav-item">
                                    <a href="{{ url('/admin/invoices/create') }}" class="nav-link">@lang('site.create-invoice')</a>
                                </li>
                                @endcan



                                @can('access','view_invoice_categories')
                                <li class="nav-item">
                                    <a href="{{ route('admin.invoice-categories.index') }}" class="nav-link">@lang('amenu.categories')</a>
                                </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('access-group','vacancies')
                    <li class="nav-item">
                        <a class="nav-link" href="#nav-vacancies" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-vacancies">
                            <i class="fa fa-clipboard text-red"></i>
                            <span class="nav-link-text">@lang('amenu.vacancies')</span>
                        </a>
                        <div class="collapse" id="nav-vacancies">
                            <ul class="nav nav-sm flex-column">
                                @can('access','view_vacancies')
                                <li class="nav-item">
                                    <a href="{{ route('admin.vacancies.index') }}" class="nav-link">@lang('site.view-vacancies')</a>
                                </li>
                                @endcan

                                @can('access','create_vacancy')
                                <li class="nav-item">
                                    <a href="{{ route('admin.vacancies.create') }}" class="nav-link">@lang('site.create-vacancy')</a>
                                </li>
                                @endcan

                                @can('access','view_vacancy_categories')
                                <li class="nav-item">
                                    <a href="{{ route('admin.job-categories.index') }}" class="nav-link">@lang('amenu.categories')</a>
                                </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('access-group','contracts')
                        <li class="nav-item">
                            <a class="nav-link" href="#nav-contracts" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-contracts">
                                <i class="fa fa-handshake text-primary"></i>
                                <span class="nav-link-text">@lang('site.contracts')</span>
                            </a>
                            <div class="collapse" id="nav-contracts">
                                <ul class="nav nav-sm flex-column">
                                    @can('access','view_contracts')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.contracts.index') }}" class="nav-link">@lang('site.manage-contracts')</a>
                                        </li>
                                    @endcan

                                    @can('access','manage_contract_templates')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.contract-templates.index') }}" class="nav-link">@lang('site.contract-templates')</a>
                                        </li>
                                    @endcan

                                </ul>
                            </div>
                        </li>
                    @endcan


                    @can('access-group','tests')
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-tests" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tests">
                            <i class="fa fa-question-circle text-orange"></i>
                            <span class="nav-link-text">@lang('site.tests')</span>
                        </a>
                        <div class="collapse" id="navbar-tests">
                            <ul class="nav nav-sm flex-column">
                                @can('access','create_test')
                                <li class="nav-item">
                                    <a href="{{ route('admin.tests.create') }}" class="nav-link">@lang('site.create-test')</a>
                                </li>
                                @endcan

                                @can('access','view_tests')
                                <li class="nav-item">
                                    <a href="{{ route('admin.tests.index') }}" class="nav-link">@lang('site.all-tests')</a>
                                </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-components" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-components">
                            <i class="ni ni-email-83 text-info"></i>
                            <span class="nav-link-text">@lang('site.messaging')</span>
                        </a>
                        <div class="collapse" id="navbar-components">
                            <ul class="nav nav-sm flex-column">
                                @can('access-group','emails')
                                <li class="nav-item">
                                    <a class="nav-link" href="#nav-messaging" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-messaging">

                                        @lang('amenu.email')
                                    </a>
                                    <div class="collapse" id="nav-messaging">
                                        <ul class="nav nav-sm flex-column">
                                            @can('access','create_email')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.emails.create') }}" class="nav-link">@lang('site.create-message')</a>
                                            </li>
                                            @endcan

                                            @can('access','view_emails')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.emails.index') }}" class="nav-link">@lang('site.all-messages')</a>
                                            </li>

                                            <li class="nav-item">
                                                <a href="{{ route('admin.emails.index') }}?sent=1" class="nav-link">@lang('site.sent-messages')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.emails.index') }}?sent=0" class="nav-link">@lang('site.pending-messages')</a>
                                            </li>
                                            @endcan

                                            @can('access','view_email_resources')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.email-resources.index') }}" class="nav-link">@lang('site.email-resources')</a>
                                            </li>
                                            @endcan

                                            @can('access','view_email_templates')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.email-templates.index') }}" class="nav-link">@lang('site.email-templates')</a>
                                            </li>
                                            @endcan


                                        </ul>
                                    </div>
                                </li>
                                @endcan

                                @can('access-group','text_messaging')
                                <li class="nav-item">
                                    <a class="nav-link" href="#nav-sms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-sms">

                                        @lang('site.text-messaging')
                                    </a>
                                    <div class="collapse" id="nav-sms">
                                        <ul class="nav nav-sm flex-column">
                                            @can('access','create_text_message')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.sms.create') }}" class="nav-link">@lang('site.create-message')</a>
                                            </li>
                                            @endcan

                                            @can('access','view_text_message')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.sms.index') }}" class="nav-link">@lang('site.all-messages')</a>
                                            </li>

                                            <li class="nav-item">
                                                <a href="{{ route('admin.sms.index') }}?sent=1" class="nav-link">@lang('site.sent-messages')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.sms.index') }}?sent=0" class="nav-link">@lang('site.pending-messages')</a>
                                            </li>
                                            @endcan

                                            @can('access','view_sms_templates')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.sms-templates.index') }}" class="nav-link">@lang('site.sms-templates')</a>
                                            </li>
                                            @endcan



                                        </ul>
                                    </div>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-content" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-content">
                            <i class="ni ni-books text-info"></i>
                            <span class="nav-link-text">@lang('site.content')</span>
                        </a>
                        <div class="collapse" id="navbar-content">
                            <ul class="nav nav-sm flex-column">

                                @can('access-group','articles')
                                <li class="nav-item">
                                    <a class="nav-link" href="#nav-articles" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-articles">
                                        @lang('amenu.articles')
                                    </a>
                                    <div class="collapse" id="nav-articles">
                                        <ul class="nav nav-sm flex-column">
                                            @can('access','view_articles')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.articles.index') }}" class="nav-link">@lang('site.manage-articles')</a>
                                            </li>
                                            @endcan
                                            @can('access','create_article')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.articles.create') }}" class="nav-link">@lang('site.create-post')</a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                                @endcan

                                @can('access-group','blog')
                                <li class="nav-item">
                                    <a class="nav-link" href="#nav-blog" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-blog">
                                        @lang('amenu.blog')
                                    </a>
                                    <div class="collapse" id="nav-blog">
                                        <ul class="nav nav-sm flex-column">
                                            @can('access','view_blogs')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.blog-posts.index') }}" class="nav-link">@lang('site.manage-posts')</a>
                                            </li>
                                            @endcan

                                            @can('access','create_blog')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.blog-posts.create') }}" class="nav-link">@lang('site.create-post')</a>
                                            </li>
                                            @endcan

                                            @can('access','manage_blog_categories')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.blog-categories.index') }}" class="nav-link">@lang('site.manage-categories')</a>
                                            </li>
                                            @endcan

                                        </ul>
                                    </div>
                                </li>
                                @endcan





                            </ul>
                        </div>
                    </li>






                    @can('access-group','settings')
                    <li class="nav-item">
                        <a class="nav-link" href="#nav-settings" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-settings">
                            <i class="ni ni-settings text-default"></i>
                            <span class="nav-link-text">@lang('amenu.settings')</span>
                        </a>
                        <div class="collapse" id="nav-settings">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings',['group'=>'general'])  }}" class="nav-link">@lang('amenu.general')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.payment-methods') }}" class="nav-link">@lang('amenu.payment-methods')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.templates') }}" class="nav-link">@lang('site.templates')</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#nav-menus" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-menus">

                                        @lang('site.menus')
                                    </a>
                                    <div class="collapse" id="nav-menus">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{ route('admin.menus.header') }}" class="nav-link">@lang('site.header-menu')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.menus.footer') }}" class="nav-link">@lang('site.footer-menu')</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.sms-gateways') }}" class="nav-link">@lang('site.sms-gateways')</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.settings',['group'=>'captcha'])  }}" class="nav-link">@lang('site.setting-captcha')</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#nav-forms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-forms">

                                         @lang('amenu.forms')
                                    </a>
                                    <div class="collapse" id="nav-forms">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{ route('admin.order-forms.index') }}" class="nav-link">@lang('site.order-forms')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.employer-field-groups.index') }}" class="nav-link">@lang('amenu.employer-profile')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.candidate-field-groups.index') }}" class="nav-link">@lang('amenu.candidate-profile')</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.language') }}" class="nav-link">@lang('amenu.language')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings',['group'=>'order'])  }}" class="nav-link">@lang('amenu.order-settings')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings',['group'=>'mail'])  }}" class="nav-link">@lang('amenu.email-settings')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings',['group'=>'social'])  }}" class="nav-link">@lang('amenu.social-login')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings',['group'=>'image'])  }}" class="nav-link">@lang('amenu.logo-icon')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.admins.index') }}" class="nav-link">@lang('amenu.administrators')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}" class="nav-link">@lang('site.manage-users')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}" class="nav-link">@lang('site.roles')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.frontend') }}" class="nav-link">@lang('site.disable-frontend')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.api-tokens.index') }}" class="nav-link">@lang('site.api-tokens')</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endcan



                </ul>

            </div>
        </div>
    </div>
</nav>
<!-- Main content -->
<div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Search form -->
                @hasSection('search-form')
                <form  method="GET" action="@yield('search-form')"  class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                   @yield('search-form-extras')
                    <div class="form-group mb-0">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input value="{{ request('search') }}" name="search"  class="form-control" placeholder="@lang('site.search')" type="text">
                        </div>
                    </div>
                    <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </form>
                @else
                        <strong class="text-white d-none d-sm-none d-md-block">@yield('pageTitle',__('site.admin'))</strong>
                @endif
                <!-- Navbar links -->
                <ul class="navbar-nav align-items-center ml-md-auto">
                    <li class="nav-item d-xl-none">
                        <!-- Sidenav toggler -->
                        <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </div>
                    </li>
                    @hasSection('search-form')
                    <li class="nav-item d-sm-none">
                        <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                            <i class="ni ni-zoom-split-in"></i>
                        </a>
                    </li>
                    @endif


                </ul>
                    @if (empty($__env->yieldContent('search-form')))
                    <strong class="text-white d-block d-sm-none">@yield('pageTitle',__('site.admin'))</strong>
                    @endif
                <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                    <li class="nav-item">

                    </li>

                    <li class="nav-item dropdown">

                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                   <img   src="{{ asset('img/man.jpg') }}">
                  </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span class="mb-0 text-sm  font-weight-bold">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">@lang('site.welcome')!</h6>
                            </div>
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <i class="ni ni-single-02"></i>
                                <span>@lang('site.my-profile')</span>
                            </a>

                            <div class="dropdown-divider"></div>
                            <a  href="#"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="dropdown-item">
                                <i class="ni ni-user-run"></i>
                                <span>@lang('site.logout')</span>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="int_hide">
                                    @csrf
                                </form>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Header -->
    <!-- Header -->
    <div class="header  bg-primary  pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="@hasSection('top-buttons') col-lg-8 @else col-lg-12 @endif col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">

                            @hasSection('page-title')
                             @yield('page-title')
                            @else
                                @hasSection('search-form')
                                    @yield('pageTitle',__('site.admin'))
                                @endif
                            @endif
                        </h6>
                        @hasSection('breadcrumb')
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i></a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                        @endif

                    </div>
                    @hasSection('top-buttons')
                    <div class="col-lg-4 col-5 text-right">
                        @yield('top-buttons')
                    </div>
                    @endif

                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if (count($errors) > 0)
                            <div class="int_pldpr50">
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif


                        <div class="flash-message int_pldpr50"  >
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))

                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                @endif
                            @endforeach
                            @if(Session::has('flash_message'))

                                <p class="alert alert-success">{{ Session::get('flash_message') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                            @endif
                        </div> <!-- end .flash-message -->
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
       @yield('content')

        <!-- Footer -->
        <footer class="footer pt-0">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6">
                    <div class="copyright text-center text-lg-left text-muted">
                        &copy; {{ date('Y') }} {{ config('app.name') }}
                    </div>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
        </footer>
    </div>
</div>
<!-- Argon Scripts -->
<!-- Core -->
<script src="{{ asset('themes/argonpro/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('themes/argonpro/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('themes/argonpro/assets/vendor/js-cookie/js.cookie.js') }}"></script>
<script src="{{ asset('themes/argonpro/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('themes/argonpro/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
<!-- Optional JS -->
<script src="{{ asset('themes/argonpro/assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('themes/argonpro/assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
<script src="{{ asset('themes/argonpro/assets/vendor/jvectormap-next/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('themes/argonpro/assets/js/vendor/jvectormap/jquery-jvectormap-world-mill.js') }}"></script>
<!-- Argon JS -->
<script src="{{ asset('themes/argonpro/assets/js/argon.js?v=1.1.0') }}"></script>
<!-- Demo JS - remove this in your project -->
@if(false)
<script src="{{ asset('themes/argonpro/assets/js/demo.min.js') }}"></script>
@endif
<script src="{{ asset('js/lib.js') }}" type="text/javascript"></script>
@yield('footer')
</body>

</html>
