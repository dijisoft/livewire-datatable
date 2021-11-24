@props(['url' => null])

<div class="nk-tb-item"
    {{ $attributes }}

    @if ($url)
        onclick="window.location='{{ $url }}';"
        style="cursor:pointer"
    @endif
>
    {{ $slot }}
</div>
