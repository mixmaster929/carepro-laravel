@extends($userLayout)

@section('page-title',__('site.dashboard'))
@section('content')
    <div class="row row-sm">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-dashboard-twentytwo">
                <div class="media">
                    <div class="media-icon bg-purple"><i class="fa fa-users"></i></div>
                    <div class="media-body">
                        <h6>{{ $orderTotal }}</h6>
                        <span><a href="{{ route('employer.orders') }}">@lang('site.orders')</a></span>
                    </div>
                </div>
            </div><!-- card -->
        </div><!-- col -->
        <div class="col-sm-6 col-xl-3 mg-t-20 mg-sm-t-0">
            <div class="card card-dashboard-twentytwo">
                <div class="media">
                    <div class="media-icon bg-primary"><i class="fa fa-file-invoice-dollar"></i></div>
                    <div class="media-body">
                        <h6>{{ $invoiceTotal }}</h6>
                        <span><a href="{{ route('user.billing.invoices') }}">@lang('site.invoices')</a></span>
                    </div>
                </div>
            </div><!-- card -->
        </div><!-- col-3 -->
        <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card card-dashboard-twentytwo">
                <div class="media">
                    <div class="media-icon bg-pink"><i class="fa fa-user-tie"></i></div>
                    <div class="media-body">
                        <h6>{{ $placementTotal }}</h6>
                        <span><a href="{{ route('employer.placements') }}">@lang('site.placements')</a></span>
                    </div>
                </div>
            </div><!-- card -->
        </div><!-- col -->
        <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card card-dashboard-twentytwo">
                <div class="media">
                    <div class="media-icon bg-teal"><i class="fa fa-calendar-alt"></i></div>
                    <div class="media-body">
                        <h6>{{ $interviewTotal }}</h6>
                        <span><a href="{{ route('employer.interviews') }}">@lang('site.interviews')</a></span>
                    </div>
                </div>
            </div><!-- card -->
        </div><!-- col -->

    </div>

    @if(!empty(setting('general_employer_dashboard_notice')))
        <br/>
        <div class="card bd-0">
            <div class="card-header tx-medium bd-0 tx-white bg-orange">
                <i class="fa fa-info-circle"></i>  @lang('site.information')
            </div><!-- card-header -->
            <div class="card-body bd bd-t-0">
                {!! setting('general_employer_dashboard_notice') !!}

            </div><!-- card-body -->

        </div>
    @endif

    <br/>

    @if($orders->count()==0)

        <div class="card bd-0">
            <div class="card-header tx-medium bd-0 tx-white bg-indigo">
                <i class="fa fa-user-plus"></i>  @lang('site.create-order')
            </div><!-- card-header -->
            <div class="card-body bd bd-t-0">
                <div class="card-text">
                    @lang('site.first-order')<br/> <br/>
                </div>
                <a href="@route('order-forms')" class="btn btn-success rounded btn-lg"><i class="fa fa-user-plus"></i> @lang('site.new-order')</a>

            </div><!-- card-body -->

        </div>




        <br/>
        @endif



    <div class="card bd-0">
        <div class="card-header tx-medium bd-0 tx-white bg-indigo">
            <i class="fa fa-file-invoice-dollar"></i>  @lang('site.invoices')
        </div><!-- card-header -->
        <div class="card-body bd bd-t-0">
            @if($invoices->count()==0)
            <p class="mg-b-0">@lang('site.no-records')</p>
            @else
                @include('account.billing.invoice-list',['invoices'=>$invoices])
            @endif

        </div><!-- card-body -->
        <div class="card-footer bd-t tx-center">
            <a href="{{ route('user.billing.invoices') }}">@lang('site.view-all')</a>
        </div>
    </div>




    <br/>
    <div class="card bd-0">
        <div class="card-header tx-medium bd-0 tx-white bg-indigo">
            <i class="fa fa-users"></i>  @lang('site.orders')
        </div><!-- card-header -->
        <div class="card-body bd bd-t-0">
            @if($orders->count()==0)
                <p class="mg-b-0">@lang('site.no-records')</p>
            @else
                @include('employer.order.order-list',['orders'=>$orders])
            @endif

        </div><!-- card-body -->
        <div class="card-footer bd-t tx-center">
            <a href="{{ route('employer.orders') }}">@lang('site.view-all')</a>
        </div>
    </div>



    <br/>
    <div class="card bd-0">
        <div class="card-header tx-medium bd-0 tx-white bg-indigo">
            <i class="fa fa-calendar-alt"></i>  @lang('site.placements')
        </div><!-- card-header -->
        <div class="card-body bd bd-t-0">
            @if($placements->count()==0)
                <p class="mg-b-0">@lang('site.no-records')</p>
            @else
                @include('employer.placement.placement-list',['employments'=>$placements,'perPage'=>5])
            @endif

        </div><!-- card-body -->
        <div class="card-footer bd-t tx-center">
            <a href="{{ route('employer.placements') }}">@lang('site.view-all')</a>
        </div>
    </div>








@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/candidate-dashboard.css') }}">

    @endsection
