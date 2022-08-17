@extends($userLayout)
@section('pageTitle',__('site.contracts'))
@section('page-title',__('site.contracts'))

@section('content')

    <div class="card-box">
        <div class="table-responsive">
            <table class="table-stripped table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('site.name')</th>
                    <th>@lang('site.other-signatories')</th>
                    <th>@lang('site.created-on')</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($contracts as $contract)
                    <tr >
                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                        <td>{{ $contract->name }}
                        </td>
                        <td>
                            @foreach($contract->users()->where('id','!=',\Illuminate\Support\Facades\Auth::user()->id)->get() as $user)
                                {{ $user->name }} ({{ $user->pivot->signed==1? __('site.signed'):__('site.pending') }}) @if(!$loop->last),  @endif
                            @endforeach
                        </td>
                        <td>{{ $contract->created_at->format('d/M/Y') }}</td>
                        <td>

                            @if($contract->users()->where('id',\Illuminate\Support\Facades\Auth::user()->id)->first()->pivot->signed==0)
                                <a  class="btn btn-primary" href="{{ route('user.contract.show',['contract'=>$contract->id]) }}"><i class="fa fa-signature"></i> @lang('site.view-sign')</a>
                            @else
                                <a class="btn btn-success" href="{{ route('user.contract.download',['contract'=>$contract->id]) }}"><i class="fa fa-download"></i> @lang('site.download')</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $contracts->links() }}
    </div>

@endsection

@section('footer')
    <script>
        $(function(){
            'use strict'

            $('[data-toggle="tooltip"]').tooltip();

        });
    </script>
@endsection
