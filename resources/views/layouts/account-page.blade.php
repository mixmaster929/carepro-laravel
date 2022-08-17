@extends('layouts.account')


@section('content')

    <div class="row">
        <div class="col">
            <div class="card shadow">
                @hasSection('page-title')
                <div class="card-header border-0">
                    <h3 class="mb-0">@yield('page-title')</h3>
                </div>
                @endif

                <div class="card-body">
                    @yield('page-content')
                </div>

                @hasSection('page-footer')
                <div class="card-footer py-4">
                    @yield('page-footer')
                </div>
                @endif

            </div>
        </div>
    </div>






@endsection