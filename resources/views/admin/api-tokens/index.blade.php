@extends('layouts.admin-page')



@section('pageTitle',__('site.api-tokens'))
@section('page-title',__('site.api-tokens'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        <a href="{{ url('/admin/api-tokens/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new') apiToken">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.token')</th>
                                        <th>@lang('site.enabled')</th><th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($apitokens as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td>{{ $item->token }} <button class="btn btn-sm btn-primary" onclick="copyToBoard('{{ $item->token }}')"><i class="fa fa-copy"></i></button></td>
                                        <td>{{ boolToString($item->enabled) }}</td>
                                        <td>

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->

                                                    <a class="dropdown-item" href="{{ url('/admin/api-tokens/' . $item->id . '/edit') }}">@lang('site.edit')</a>



                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>




                                                </div>
                                            </div>

                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/api-tokens' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp""display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $apitokens->appends(request()->input())->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.css') }}">

@endsection


@section('footer')
    <script src="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
    <script>
        function copyToBoard(text){

            copyToClipboard(text)
                .then(() => $.toast('@lang('site.copied-clipboard')'))
                .catch(() => console.log('error'));

        }
    </script>
@endsection
