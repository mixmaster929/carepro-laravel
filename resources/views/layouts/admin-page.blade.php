@extends('layouts.admin')


@section('content')

    <div class="row">
        <div class="col">
            <div class="card shadow">


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

