@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-primary border-0">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="card-title text-uppercase text-muted mb-0 text-white">@lang('site.users')</h6>
                            <span class="h2 font-weight-bold mb-0 text-white">{{ $totalUsers }}@if(saas())/{{ USER_LIMIT }}@endif</span>

                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-users"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('admin.employers.index') }}">@lang('site.employers') ({{ \App\User::where('role_id',2)->count() }})</a>
                                <a class="dropdown-item" href="{{ route('admin.candidates.index') }}">@lang('site.candidates') ({{ \App\User::where('role_id',3)->count() }})</a>
                                <a class="dropdown-item" href="{{ route('admin.admins.index') }}">@lang('site.administrators') ({{ \App\User::where('role_id',1)->count() }})</a>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <a href="#" class="text-nowrap text-white font-weight-600">@lang('site.total-user-count')</a>
                    </p>
                </div>
            </div>
        </div>
        @can('access','view_orders')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-info border-0">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="card-title text-uppercase text-muted mb-0 text-white">@lang('site.total-orders')</h6>
                            <span class="h2 font-weight-bold mb-0 text-white">{{ $totalSales }}</span>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-neutral mr-0">
                                <i class="fas fa-hand-holding-usd"></i>
                            </button>

                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <a href="#" class="text-nowrap text-white font-weight-600">@lang('site.orders-total')</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-danger border-0">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="card-title text-uppercase text-muted mb-0 text-white">@lang('site.month-orders')</h6>
                            <span class="h2 font-weight-bold mb-0 text-white">{{ $monthSales }}</span>

                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-neutral mr-0">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <a href="#" class="text-nowrap text-white font-weight-600">@lang('site.orders-month')</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-default border-0">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="card-title text-uppercase text-muted mb-0 text-white">@lang('site.annual-orders')</h6>
                            <span class="h2 font-weight-bold mb-0 text-white">{{ $yearSales }}</span>

                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-neutral mr-0">
                                <i class="fas fa-search-dollar"></i>
                            </button>

                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <a href="#" class="text-nowrap text-white font-weight-600">@lang('site.orders-annual')</a>
                    </p>
                </div>
            </div>
        </div>
        @endcan
    </div>
    @can('access','view_invoices')
    <div class="row">
        <div class="col-xl-7 mb-5 mb-xl-0">
            <div class="card bg-gradient-default shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-light ls-1 mb-1">@lang('site.stats')</h6>
                            <h2 class="text-white mb-0">@lang('site.approved-invoices')</h2>
                        </div>
                        <div class="col">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="chart-sales2" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">






            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">@lang('stats')</h6>
                            <h2 class="mb-0">@lang('site.orders')</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">



                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home">@lang('site.all')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu1">@lang('site.completed')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu2">@lang('site.cancelled')</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content int_pt10" >
                        <div class="tab-pane container active" id="home">

                            <!-- Chart -->
                            <div class="chart">
                                <canvas id="chart-orders2" class="chart-canvas"></canvas>
                            </div>

                        </div>
                        <div class="tab-pane container fade" id="menu1">
                            <!-- Chart -->
                            <div class="chart">
                                <canvas id="chart-orders3" class="chart-canvas"></canvas>
                            </div>

                        </div>
                        <div class="tab-pane container fade" id="menu2">
                            <!-- Chart -->
                            <div class="chart">
                                <canvas id="chart-orders4" class="chart-canvas"></canvas>
                            </div>

                        </div>
                    </div>








                </div>
            </div>





        </div>
    </div>
    @endcan


    @can('access','view_orders')
    <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">@lang('site.recent-orders')</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ url('/admin/orders') }}" class="btn btn-sm btn-primary">@lang('site.view-all')</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.employer')</th>
                                    <th>@lang('site.added-on')</th>
                                    <th>@lang('site.interview-date')</th>
                                    <th>@lang('site.status')</th>
                                    <th>@lang('site.shortlist')</th>
                                    <th>@lang('site.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentOrders as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
                                        <td>
                                            @if(!empty($item->interview_date))
                                                {{ \Illuminate\Support\Carbon::parse($item->interview_date)->format('d/M/Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ orderStatus($item->status) }}
                                        </td>
                                        <td>
                                            {{ $item->candidates()->count() }}
                                        </td>
                                        <td>

                                            @can('access','view_order_comments')
                                            <a href="{{ route('admin.order-comments.index',['order'=>$item->id]) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-comments" aria-hidden="true"></i> @lang('site.comments')({{ $item->orderComments()->count() }})</button></a>
                                            @endcan

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->
                                                    @can('access','view_order')
                                                    <a class="dropdown-item" href="{{ url('/admin/orders/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_order')
                                                    <a class="dropdown-item" href="{{ url('/admin/orders/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan


                                                    @can('access','create_employment')
                                                    <a class="dropdown-item" href="{{ route('admin.employments.create',['user'=>$item->user_id]) }}">@lang('site.create-employment')</a>
                                                    @endcan

                                                    @can('access','delete_order')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan



                                                </div>
                                            </div>

                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/orders' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
    @endcan

    @can('access','view_invoices')
    <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">@lang('site.recent-invoices')</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ url('/admin/invoices') }}" class="btn btn-sm btn-primary">@lang('site.view-all')</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th >@lang('site.user')</th>
                                    <th>@lang('site.item')</th>
                                    <th>@lang('site.amount')</th>
                                    <th>@lang('site.status')</th>
                                    <th>@lang('site.created-on')</th>
                                    <th>@lang('site.due-date')</th>
                                    <th>@lang('site.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentInvoices as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td  >

                                            <a @if($item->user->role_id==2) href="{{ url('/admin/employers/' . $item->user_id) }}" @elseif($item->user->role_id==3)  href="{{ url('/admin/candidates/' . $item->user_id) }}" @endif >{{ $item->user->name }} ({{ roleName($item->user->role_id) }})</a>


                                        </td>
                                        <td  >{{ $item->title }} </td>
                                        <td>{!! clean( check( price($item->amount)) ) !!}</td>
                                        <td>{{ ($item->paid==1)? __('site.paid'):__('site.unpaid') }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/M/Y') }}
                                        </td>
                                        <td>
                                            @if(!empty($item->due_date))
                                                {{ \Carbon\Carbon::parse($item->due_date)->format('d/M/Y') }}
                                            @endif
                                        </td>
                                        <td>


                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">


                                                    @can('access','approve_invoice')
                                                    @if($item->paid==0)
                                                        <a class="dropdown-item"  onclick="return confirm('@lang('site.confirm-approve')')"  href="{{ route('admin.invoices.approve',['invoice'=>$item->id]) }}"><i class="fa fa-thumbs-up"></i> @lang('site.approve')</a>
                                                        @endif
                                                        @endcan



                                                                <!-- Dropdown menu links -->
                                                        @can('access','view_invoice')
                                                        <a class="dropdown-item" href="{{ url('/admin/invoices/' . $item->id) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>
                                                        @endcan

                                                        @can('access','edit_invoice')
                                                        <a class="dropdown-item" href="{{ url('/admin/invoices/' . $item->id . '/edit') }}"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                                        @endcan




                                                        @can('access','delete_invoice')
                                                        <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()"><i class="fa fa-trash"></i> @lang('site.delete')</a>
                                                        @endcan



                                                </div>
                                            </div>


                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/invoices' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
    @endcan

@endsection
@section('header')

    <link href="{{ asset('css/admin/dashboard.css') }}"></link>
 @endsection

@section('footer')
    <script>
"use strict";

        //
        // Orders chart
        //

        var OrdersChart = (function() {

            //
            // Variables
            //

            var $chart = $('#chart-orders2');
            var $ordersSelect = $('[name="ordersSelect"]');


            //
            // Methods
            //

            // Init chart
            function initChart($chart) {

                // Create chart
                var ordersChart = new Chart($chart, {
                    type: 'bar',
                    options: {
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    lineWidth: 1,
                                    color: '#dfe2e6',
                                    zeroLineColor: '#dfe2e6'
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (!(value % 10)) {
                                            //return '$' + value + 'k'
                                            return value
                                        }
                                    }
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(item, data) {
                                    var label = data.datasets[item.datasetIndex].label || '';
                                    var yLabel = item.yLabel;
                                    var content = '';

                                    if (data.datasets.length > 1) {
                                        content += '' + label + '';
                                    }

                                    content += '' + yLabel + '';

                                    return content;
                                }
                            }
                        }
                    },
                    data: {
                        labels: {!! clean($monthList) !!},
                        datasets: [{
                            label: '@lang('site.sales')',
                            data: {!! clean($monthSaleCount) !!}
                    }]
        }
        });

        // Save to jQuery object
        $chart.data('chart', ordersChart);
        }


        // Init chart
        if ($chart.length) {
            initChart($chart);
        }

        })();



        //
        // Orders chart
        //

        var OrdersChart2 = (function() {

            //
            // Variables
            //

            var $chart2 = $('#chart-orders3');
            var $ordersSelect2 = $('[name="ordersSelect"]');


            //
            // Methods
            //

            // Init chart
            function initChart($chart2) {

                // Create chart
                var ordersChart2 = new Chart($chart2, {
                    type: 'bar',
                    options: {
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    lineWidth: 1,
                                    color: '#dfe2e6',
                                    zeroLineColor: '#dfe2e6'
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (!(value % 10)) {
                                            //return '$' + value + 'k'
                                            return value
                                        }
                                    }
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(item, data) {
                                    var label = data.datasets[item.datasetIndex].label || '';
                                    var yLabel = item.yLabel;
                                    var content = '';

                                    if (data.datasets.length > 1) {
                                        content += '' + label + '';
                                    }

                                    content += '' + yLabel + '';

                                    return content;
                                }
                            }
                        }
                    },
                    data: {
                        labels: {!! clean($monthList) !!},
            datasets: [{
                label: '@lang('site.sales')',
                data: {!! clean($monthSaleCompCount) !!}
        }]
        }
        });

        // Save to jQuery object
        $chart2.data('chart', ordersChart2);
        }


        // Init chart
        if ($chart2.length) {
            initChart($chart2);
        }

        })();



        //
        // Orders chart
        //

        var OrdersChart3 = (function() {

            //
            // Variables
            //

            var $chart3 = $('#chart-orders4');
            var $ordersSelect3 = $('[name="ordersSelect"]');


            //
            // Methods
            //

            // Init chart
            function initChart($chart3) {

                // Create chart
                var ordersChart3 = new Chart($chart3, {
                    type: 'bar',
                    options: {
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    lineWidth: 1,
                                    color: '#dfe2e6',
                                    zeroLineColor: '#dfe2e6'
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (!(value % 10)) {
                                            //return '$' + value + 'k'
                                            return value
                                        }
                                    }
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(item, data) {
                                    var label = data.datasets[item.datasetIndex].label || '';
                                    var yLabel = item.yLabel;
                                    var content = '';

                                    if (data.datasets.length > 1) {
                                        content += '' + label + '';
                                    }

                                    content += '' + yLabel + '';

                                    return content;
                                }
                            }
                        }
                    },
                    data: {
                        labels: {!! clean($monthList) !!},
            datasets: [{
                label: '@lang('site.sales')',
                data: {!! clean($monthSaleCanCount) !!}
        }]
        }
        });

        // Save to jQuery object
        $chart3.data('chart', ordersChart3);
        }


        // Init chart
        if ($chart3.length) {
            initChart($chart3);
        }

        })();


        //
        // Charts
        //

        'use strict';

        //
        // Sales chart
        //

        var SalesChart = (function() {

            // Variables

            var $chart = $('#chart-sales2');


            // Methods

            function init($chart) {

                var salesChart = new Chart($chart, {
                    type: 'line',
                    options: {
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    lineWidth: 1,
                                    color: Charts.colors.gray[900],
                                    zeroLineColor: Charts.colors.gray[900]
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (!(value % 10)) {
                                            return '{{ $currency }}' + value ;
                                        }
                                    }
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(item, data) {
                                    var label = data.datasets[item.datasetIndex].label || '';
                                    var yLabel = item.yLabel;
                                    var content = '';

                                    if (data.datasets.length > 1) {
                                        content += '' + label + '';
                                    }

                                    content += '{{ $currency }}' + yLabel + '';
                                    return content;
                                }
                            }
                        }
                    },
                    data: {
                        labels: {!! clean($monthList) !!},
            datasets: [{
                label: '@lang('site.sales')',
                data: {!! clean($monthSaleData) !!}
        }]
        }
        });

        // Save to jQuery object

        $chart.data('chart', salesChart);

        };


        // Events

        if ($chart.length) {
            init($chart);
        }

        })();
    </script>


@endsection
