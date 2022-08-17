<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('site.name')</th>
            <th>@lang('site.minutes-allowed')</th>
            <th>@lang('site.attempts-allowed')</th>
            <th>@lang('site.passmark')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($tests as $test)
            <tr>
                <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                <td>{{ $test->name }}</td>
                <td>{{ empty($test->minutes) ? __('site.unlimited'):$test->minutes.' '.__('site.minutes') }}</td>
                <td>{{ empty($test->allow_multiple)? __('site.single'):__('site.multiple') }}</td>
                <td>{{ $test->passmark }}%</td>
                <td>
                    @if(!\Illuminate\Support\Facades\Auth::user()->userTests()->where('test_id',$test->id)->first() || $test->allow_multiple==1)

                        <button type="button" class="btn btn-primary btn-sm rounded" data-toggle="modal" data-target="#testModal{{ $test->id }}">
                            <i class="fa fa-play"></i> @lang('site.take-test')
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="testModal{{ $test->id }}" tabindex="-1" role="dialog" aria-labelledby="testModal{{ $test->id }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="testModal{{ $test->id }}Label">{{ $test->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {!! clean( check($test->description) ) !!}
                                        @if($test->allow_multiple==0)
                                            <div>
                                                @lang('site.single-attempt-notice')
                                            </div>
                                        @endif
                                        <div>
                                            <strong>@lang('site.time-limit'):</strong> {{ empty($test->minutes) ? __('site.unlimited'):$test->minutes.' '.__('site.minutes') }}
                                        </div>
                                    </div>
                                    <div class="modal-footer">

                                        <a class="btn btn-primary btn-block" href="{{ route('candidate.tests.start',['test'=>$test->id]) }}">
                                            <i class="fa fa-play"></i> @lang('site.start-test')
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>







                    @else
                        <strong>@lang('site.test-taken')</strong>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->userTests()->where('test_id',$test->id)->count()>0 && $test->show_result==1 )
                        <a class="btn btn-success btn-sm rounded" href="{{ route('candidate.tests.results',['test'=>$test->id]) }}">
                            <i class="fa fa-poll-h"></i>  @lang('site.view-results')</a>
                    @endif

                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
