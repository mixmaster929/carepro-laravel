@extends($userLayout)
@section('page-title',__('site.billing-addresses'))
@section('content')
    <div class="card-box">
        <div>
            <a class="btn btn-primary" href="{{ route('user.billing-address.create') }}"><i class="fa fa-plus"></i> @lang('site.add-address')</a>
            <br/>  <br/>
        </div>
        <div class="table-responsive">
            <table class="table table-stripped">

                <thead>
                <tr>
                    <th>@lang('site.name')</th>
                    <th>@lang('site.city')</th>
                    <th>@lang('site.state-province')</th>
                    <th>@lang('site.country')</th>
                    <th>@lang('site.default')</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($addresses as $address)
                <tr>
                    <td>{{ $address->name }}</td>
                    <td>{{ $address->city }}</td>
                    <td>{{ $address->state }}</td>
                    <td>{{ $address->country->name }}</td>
                    <td>{{ boolToString($address->default)}}</td>
                    <td>
                        <form onsubmit="return confirm('@lang('admin.confirm-delete')')" method="post" action="{{ route('user.billing-address.destroy',['billing_address'=>$address->id]) }}">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}

                            <a class="btn btn-sm btn-primary" href="{{ route('user.billing-address.edit',['billing_address'=>$address->id]) }}"><i class="fa fa-edit"></i> @lang('site.edit')</a>

                            <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                        </form>


                    </td>
                </tr>
                    @endforeach
                </tbody>

            </table>
            {{ $addresses->links() }}
        </div>

    </div>

@endsection
