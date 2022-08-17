@extends($userLayout)

@section('page-title',__('site.test').': '.$test->name)
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('candidate.tests') }}">@lang('site.tests')</a></li>
    <li class="breadcrumb-item">@lang('site.take-test')</li>
@endsection

@section('content')
    <div class="row_">
        <div class="card">
            <div class="card-body row">
                <div class="col-md-4 ">
                    <h5>Total Questions{{ __('site.total-questions') }}</h5>
                    <h1><?php echo $totalQuestions; ?></h1>
                </div>
                <?php if(!empty($test->minutes)):?>
                <div  class="col-md-4 ">
                    <h5>@lang('site.time-limit')</h5>
                    <h1><?php echo $test->minutes; ?> @lang('site.minutes')</h1>
                </div>

                <div  class="col-md-4 ">
                    <h5>@lang('site.time-remaining')</h5>
                    <h1><span id="timespan"><?php echo $test->minutes; ?> @lang('site.minutes')</span></h1>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <br/>
        <div  >

            <div >
                <form class="int_hpw" id="testform" method="post" action="{{ route('candidate.tests.process',['userTest'=>$userTest->id])  }}">
                    @csrf
                    <div >

                        <?php $count = 0; ?>
                        @foreach($test->testQuestions as $question)
                            <?php $count++; ?>
                            <div class="card question" id="question{{ $count }}">
                                <div class="card-header">
                                    <h3 class="panel-title">{{ $count }}. {!! clean( check($question->question) ) !!}</h3>
                                </div>
                                <div class="card-body">
                                    <p >

                                        @foreach($question->testOptions as $option)


                                    <div class="radio">
                                        <label class="int_fs14">
                                            <input type="radio" id="question_op_{{ $option->id }}" name="question_{{ $option->test_question_id }}" value="{{ $option->id }}"/>

                                            {{ $option->option }}
                                        </label>
                                    </div>

                                    @endforeach


                                    </p>


                                </div>
                                <div class="card-footer ">
                                    <?php if($count > 1):?>
                                    <button type="button" onclick="showPanel('<?php echo ($count - 1); ?>')" class="prev btn btn-primary btn-lg ">@lang('site.previous')</button>
                                    <?php endif; ?>

                                    <?php if($count < $totalQuestions):?>
                                    <button  type="button"  onclick="showPanel('<?php echo $count + 1?>')"  class="next btn btn-primary btn-lg float-right">@lang('site.next')</button>
                                    <?php else: ?>
                                    <a onclick="if(confirm('@lang('site.test-submit-msg')')){$('#testform').submit()};" class="btn btn-success btn-lg float-right" href="#testform">@lang('site.finish')</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </form>
            </div>

        </div>
    </div>




@endsection


@section('footer')


    <script>
"use strict";

        var interval;
        $('.question').hide();

        showPanel(1);

        window.onbeforeunload = function(){
            return confirm("@lang('site.test-close-msg')"<?php if(empty($test->allow_multiple)){ echo '+"'.__('site.test-close-msg-add').'"'; } ?>);
        }
        <?php if(!empty($test->minutes)):?>
        var minutes = <?php echo intval($test->minutes) ?> * 60 ,
                display = document.querySelector('#timespan');
        startTimer(minutes, display);
        <?php endif; ?>

        function showPanel(id){
            $('.question').hide();
            $('#question'+id).show();
        }
        $(function(){


        });

        function startTimer(duration, display) {
            var start = Date.now(),
                    diff,
                    minutes,
                    seconds;
            function timer() {
                // get the number of seconds that have elapsed since
                // startTimer() was called
                diff = duration - (((Date.now() - start) / 1000) | 0);

                // does the same job as parseInt truncates the float
                minutes = (diff / 60) | 0;
                seconds = (diff % 60) | 0;

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (diff <= 0) {
                    // add one second so that the count down starts at the full duration
                    // example 05:00 not 04:59
                    // start = Date.now() + 1000;
                    console.log('time is up!');
                    $('#testform').submit();
                    clearInterval(interval);
                }
            };
            // we don't want to wait a full second before the timer starts
            timer();
            interval=  setInterval(timer, 1000);
        }



        $('#testform').on('submit',(function(e){

            window.onbeforeunload = function () {
                // blank function do nothing
            }


        }));
    </script>

@endsection
