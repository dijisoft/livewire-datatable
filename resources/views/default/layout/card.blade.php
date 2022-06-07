<div 
    class="livewire-datatable"
    @if($loader) wire:init="loadTable" @endif 
    @if (is_numeric($refresh)) 
        wire:poll.{{ $refresh }}ms 
    @elseif(is_string($refresh)) 
        @if ($refresh==='.keep-alive' || $refresh==='keep-alive' )
            wire:poll.keep-alive 
        @elseif($refresh==='.visible' || $refresh==='visible' ) 
            wire:poll.visible 
        @else 
            wire:poll="{{ $refresh }}"
        @endif 
     @endif>

    @unless($this->limit)
        @if(isset($views['header']) && !in_array('custom-header', $hide)))
            @include($views['header'])
        @elseif(!in_array('filters', $hide))
        <div class="nk-block-head nk-block-head">
            <div class="nk-block-between">
                <div class="nk-block-head-content d-flex">
                    @if($title && !(in_array('title', $hide)))
                    <h3 class="nk-block-title page-title">{!! $title !!}</h3>
                    @endif
                    @include('datatables::default.includes.btn-filters')
                </div>
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        @if(!(in_array('mobile-menu', $hide)))
                        <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                        @endif
                        <div class="toggle-expand-content" data-content="pageMenu">
                            <ul class="nk-block-tools g-3">
                                @include('datatables::default.includes.create-button')
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endunless
    <div class="nk-block">
            @include('datatables::default.includes.offline')
            @include('datatables::default.includes.sorting-pills')
            @include('datatables::default.includes.filter-pills')
        <div @class(array_merge(['card'], $styles['class']?? [])) >
            <div class="card-inner-group">
                <div class="card-inner position-relative card-tools-toggle">
                    <div class="card-title-group justify-content-end">
                        <div class="card-tools me-n1">
                            <ul class="btn-toolbar gx-1">
                                <li class="btn-toolbar-sep"></li>
                                @include('datatables::default.includes.filters')
                                @include('datatables::default.includes.bulk-actions')
                                @include('datatables::default.includes.import-actions')
                            </ul>
                        </div>
                    </div>
                    @if ($this->showSearch && !in_array('search', $hide))
                    <div class="card-search search-wrap active" style="max-width: 10rem">
                        <div class="card-body">
                            <div class="search-content">
                                <input type="text" class="form-control border-transparent form-focus-none p-0"
                                    placeholder="@lang('Search')" wire:model{{ $this->searchFilterOptions }}="filters.search">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @if ($loader && empty($readyToLoad))
                    <div class="d-flex justify-content-center py-4 ms-4">
                        <div class="spinner-border text-light" role="status">  
                            <span class="visually-hidden">@lang('Loading')...</span>
                        </div>
                    </div>
                @else
                <div class="card-inner p-0 table-responsive">
                    @include('datatables::default.includes.table')
                </div>
                <div class="card-inner">
                    @include('datatables::default.includes.pagination')
                </div>
                @endif
            </div>
        </div>
    </div>
    @foreach($components as $key => $options)
        @livewire($key, $options, key($key))
    @endforeach
</div>
