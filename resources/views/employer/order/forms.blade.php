@extends($userLayout)

@section('page-title',$title)
@section('breadcrumb')
    <li class="breadcrumb-item">@lang('site.order-forms')</li>
@endsection

@section('content')
    @if($formList->count()==0)
        <h5>@lang('site.no-forms')</h5>
    @endif
<h5>@lang('site.order-form-help')</h5>
    @foreach($formList as $form)
        <div class="card int_mb30px" >
            <div class="card-header  tx-medium bd-0 tx-white bg-indigo">
                {{ $form->name }}
            </div>
            <div class="card-body">
                <div class="card-text">
                   {!! clean( $form->description ) !!}
                </div>
                <a href="@route('order-form',['orderForm'=>$form->id])?nodesc" class="btn btn-primary rounded btn-lg"><i class="fa fa-user-plus"></i> @lang('site.fill-form')</a>
            </div>
        </div>

    @endforeach


@endsection
