@props(['styles' => []])

<div class="nk-tb-list nk-tb-ulist nk-tb-tnx
    @if(in_array('compact', $styles)) is-compact @endif
    @if(in_array('separated', $styles)) is-separate @else bg-white @endif">
    <div class="nk-tb-item nk-tb-head
        @if(in_array('header-light-grey', $styles)) tb-odr-head @endif
        @if(in_array('header-stronger', $styles)) tb-strong-head @endif
        @if(in_array('header-upper', $styles)) tb-tnx-head @endif">
        {{ $head }}
    </div>
    {{ $body }}
</div>
