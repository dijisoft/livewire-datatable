<div @if($loader) wire:init="loadTable" @endif
    @if (is_numeric($refresh))
        wire:poll.{{ $refresh }}ms
    @elseif(is_string($refresh))
        @if ($refresh === '.keep-alive' || $refresh === 'keep-alive')
            wire:poll.keep-alive
        @elseif($refresh === '.visible' || $refresh === 'visible')
            wire:poll.visible
        @else
            wire:poll="{{ $refresh }}"
        @endif
    @endif
    class="container-fluid p-0"
>
    {{-- <p>FILTERS: {{ json_encode($filters) }} CUSTOM: {{ json_encode($customFilters) }}</p> --}}
    @unless($this->limit)

    @if($customFiltersView)
        @include($customFiltersView)
    @else
    <div class="nk-block-head nk-block-head @if(in_array('p-header-none', $styles)) pb-0 @endif">
        <div class="nk-block-between">
            <div class="nk-block-head-content d-flex">
                @if($title)
                <div class="d-block"><h3 class="nk-block-title page-title">{!! $title !!}</h3></div>
                @endif
                @include('datatables::default.includes.btn-filters')
            </div>
            <div class="nk-block-head-content">
                <div class="toggle-wrap nk-block-tools-toggle">
                    @if ($this->showSearch || $filtersView || count($filtersList) || count($bulkActions) || $create)
                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                    @endif
                    <div class="toggle-expand-content" data-content="pageMenu">
                        <ul class="nk-block-tools g-3">
                            @include('datatables::default.includes.search')
                            @include('datatables::default.includes.filters')
                            @include('datatables::default.includes.bulk-actions')
                            @include('datatables::default.includes.import-actions')
                            @include('datatables::default.includes.create-button')
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- <div class="nk-block-head nk-block-head">
        @include('datatables::default.includes.btn-filters')
    </div> --}}
    @endunless
    @if ($loader && empty($readyToLoad))
        <div class="d-flex justify-content-center py-4 ml-4">
            <div class="spinner-border text-light" role="status">  
                <span class="sr-only">Chargement...</span>
            </div>
        </div>
    @else
    <div class="nk-block">
        <div class="nk-block-head-content pl-1">
            @include('datatables::default.includes.offline')
            @include('datatables::default.includes.sorting-pills')
            @include('datatables::default.includes.filter-pills')
        </div>
        <div class="
            @if(in_array('depth', $styles)) z-depth-1-bottom @endif 
            @if(in_array('rounded', $styles)) card @endif 
            table-responsive">    
            @include('datatables::default.includes.table')
        </div>  
        @include('datatables::default.includes.pagination')
    </div>
    @endif
    @foreach($components as $key => $options)
        @livewire($key, $options, key($key))
    @endforeach
</div>