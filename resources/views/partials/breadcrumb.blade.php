@foreach($crumbs as $crumb)
    <li class="breadcrumb-item  @if ($loop->last)active @endif "  @if ($loop->last)aria-current="page" @endif ><a href="{{ $crumb['link'] }}">{{ $crumb['page'] }}</a></li>
@endforeach