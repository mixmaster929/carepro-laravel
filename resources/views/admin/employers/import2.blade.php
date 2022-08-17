@extends('admin.employers.import-layout')

@section('page-title',__('site.set-fields'))

@section('form-content')

    <form enctype="multipart/form-data" action="{{ route('admin.employers.import-save-fields') }}" method="post">
        <p>@lang('site.import-help2-text')</p>
        @csrf
        <table class="table">
            <thead>
            <tr>
                <th>@lang('site.field')</th>
                <th>@lang('site.value')</th>
            </tr>
            </thead>
            <tbody>
                @foreach($columns as $name=>$label)
                    <tr>
                        <?php
                            $append = [];
                        $append['class'] = 'form-control';
                            $reqText = '';
                        if(isset($required[$name])){
                            $append['required']= 'required';
                            $reqText= ' <span class="req">*</span>';
                        }


                        ?>
                        <td>{{ $label }}{!! clean( $reqText ) !!}</td>
                        <td>

                            {{ Form::select($name,$options,old($name,$label),$append) }}
                        </td>
                    </tr>

                    @endforeach
            </tbody>
        </table>


        <button type="submit" class="btn btn-primary">@lang('site.proceed')</button>
    </form>

@endsection
