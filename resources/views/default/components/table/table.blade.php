@props(['styles' => [], 'hide' => []])

<div 
    @if($this->isExpandable())
    x-data="{
        open: 0,
        setExpandableRowSize() {
            $el.querySelectorAll('.nk-tb-col.colspan>div>div').forEach(function (element) {
                element.style.width = $el.querySelector('.nk-tb-item').offsetWidth + 'px';
            })
        }
    }"
    x-init="setExpandableRowSize()"
    @resize.window="setExpandableRowSize()"
    @resetExpandable.window="open = 0"
    @endif

    class="nk-tb-list nk-tb-ulist nk-tb-tnx
    @if(in_array('compact', $styles)) is-compact @endif
    @if(in_array('separated', $styles)) is-separate @else bg-white @endif">

    @if(!in_array('header', $hide))
    <div class="nk-tb-item nk-tb-head
        @if(in_array('header-light-grey', $styles)) tb-odr-head @endif
        @if(in_array('header-stronger', $styles)) tb-strong-head @endif
        @if(in_array('header-upper', $styles)) tb-tnx-head @endif">
        {{ $head }}
    </div>
    @endif
    {{ $body }}
</div>
