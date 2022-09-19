@props(['url' => null])

<div {{ $attributes->class(['nk-tb-item']) }}

    @if ($url)
        onclick="window.location='{{ $url }}';"
        style="cursor:pointer"
    @endif
>
    {{ $slot }}
</div>
