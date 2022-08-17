@extends($userLayout)
@section('page-title',__('site.contract').' : '.$contract->name)
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('user.contract.index') }}">@lang('site.contracts')</a></li>
    <li class="breadcrumb-item">@lang('site.view')</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <a href="{{ route('user.contract.index') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
            <a href="javascript:window.print()"><button class="btn btn-success btn-sm"><i class="fa fa-print"></i></button></a>

            <br />
            <br />
            <h4>@lang('site.contract')</h4>
            <div style="padding: 20px; background-color: white; width: 100%; max-height: 500px; display: inline-block; overflow:auto; overflow-wrap: anywhere; border: solid 1px #CCCCCC">
                {!! $content !!}
            </div>
            <form id="signatureForm"  method="post" enctype="multipart/form-data" action="{{ route('user.contract.save',['contract'=>$contract->id]) }}">
                @csrf
            <h4 class="pt-3">@lang('site.sign-here')</h4>
            <p>@lang('site.sign-help')</p>
            <div id="signatureBox" style="border: dotted 1px #CCCCCC; position: relative">
                <img style="position:absolute !important; top: auto; min-width:50px !important; max-width:50px; !important;border:none !important;padding: 0 !important;margin:0 !important;box-shadow:0 0 0 !important;" src="{{ asset('img/signhere.gif') }}">
            </div>


                <input required type="hidden" name="signature" id="signature">

                <div class="mt-2">
                    <button class="btn btn-lg btn-block btn-primary" type="submit">@lang('site.sign-save')</button>
                </div>

            </form>
        </div>

    </div>
@endsection



@section('header')

@endsection

@section('footer')
    <script src="{{ asset('vendor/jsignature/libs/jSignature.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var sigdiv =  $("#signatureBox").jSignature({color:"#000000",lineWidth:5,'UndoButton':true});


            $('#signatureForm').submit(function(e){

                let status = confirm('{{ addslashes(__('site.sign-confirm')) }}');
                if(!status){
                    e.preventDefault();
                    return;
                }
                let datapair = sigdiv.jSignature("getData", "svgbase64");
                let src = "data:" + datapair[0] + "," + datapair[1];

                if(src.length > 340)
                {
                    $('#signature').val(src);
                }




            });

        });

    </script>
@endsection
