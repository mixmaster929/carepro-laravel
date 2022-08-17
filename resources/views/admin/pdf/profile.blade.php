<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>One Page Resume</title>
    <link rel="stylesheet" href="{{ asset('css/admin/profile.css') }}">

</head>

<body >
@if(!empty(setting('image_logo')))
    <div class="int_mt20pl30">
        <img src="{{ asset(setting('image_logo')) }}" class="mxmh" />
    </div>
@else
    <div class="sn">{{ setting('general_site_name') }}</div>
@endif

<div class="mpt">@lang('site.candidate-profile')</div>

<div id="page-wrap" >
    @if(!empty($user->candidate->picture))
    <img class="imgsize" src="{{ asset($user->candidate->picture) }}"  id="pic" />
    @endif
    <div id="contact-info" class="vcard pdl"  >

        <!-- Microformats! -->

        <h1 class="fn">{{ $user->candidate->display_name }}</h1>
        @if($full)
        <p>
            @lang('site.telephone'): <span class="tel"><?=$user->telephone?></span><br />
            @lang('site.email'): <a class="email" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
        </p>
        @endif
    </div>

    <div id="objective" class="pdl">
        <p>

        @if($full)
            <div><strong>@lang('site.name'):</strong> <?=$user->name?></div>
        @endif
            <div><strong>@lang('site.gender'):</strong> {{ gender($user->candidate->gender) }}</div>
        <div><strong>@lang('site.age'):</strong> {{  getAge(\Illuminate\Support\Carbon::parse($user->candidate->date_of_birth)->timestamp) }}</div>
        </p>
    </div>

    <div class="clear"></div>

    <dl  class="pdl10">
        <dd class="clear"></dd>
        @foreach($groups as $group)
        <dt>{{ $group->name }}</dt>
        <dd>
            @foreach($group->candidateFields()->orderBy('sort_order')->get() as $field)
                <?php
                $value = ($user->candidateFields()->where('id',$field->id)->first()) ? $user->candidateFields()->where('id',$field->id)->first()->pivot->value:'';
                ?>
                    @if($field->type=='checkbox')
                        <p><strong>{{ $field->name }}:</strong> {{ boolToString($value) }}</p>
                    @elseif($field->type=='file')
                        @if(isImage($value))
                            <h2>{{ $field->name }}</h2>
                            <p>
                                <img src="{{ asset($value) }}" class="imw700"/>
                            </p>
                        @endif

                    @else
                        <p><strong>{{ $field->name }}:</strong> {{ $value }}</p>

                    @endif

            @endforeach
        </dd>

        <dd class="clear"></dd>
        @endforeach


    </dl>

    <div class="clear"></div>

</div>

</body>

</html>
