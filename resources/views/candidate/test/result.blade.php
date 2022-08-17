@extends($userLayout)
@section('page-title',__('site.test-complete'))
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('candidate.tests') }}">@lang('site.tests')</a></li>
    <li class="breadcrumb-item">@lang('site.test-complete')</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-4 col-md-offset-3">
            <h4>@lang('site.your-score')</h4>
            <h1><?php echo $userTest->score ?>%</h1>
        </div>
        <div class="col-md-4">
            <h4>@lang('site.passmark')</h4>
            <h1><?php echo $userTest->test->passmark ?>%</h1>
        </div>
    </div>

    @if($userTest->test->passmark>0)
    <div id="testresult"   >

        <?php if($userTest->score >= $userTest->test->passmark ): ?>
        <h1 class="int_green">@lang('site.you-passed')</h1>
        <?php else: ?>
        <h1 class="int_red">@lang('site.you-failed')</h1>
        <?php endif; ?>

    </div>
    @endif

@endsection
