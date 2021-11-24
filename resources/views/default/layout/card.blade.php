<div @if($loader) wire:init="loadTable" @endif @if (is_numeric($refresh)) wire:poll.{{ $refresh }}ms @elseif(is_string($refresh)) @if ($refresh==='.keep-alive' || $refresh==='keep-alive' )
    wire:poll.keep-alive @elseif($refresh==='.visible' || $refresh==='visible' ) wire:poll.visible @else wire:poll="{{ $refresh }}" @endif @endif>

    @unless($this->limit)
    <div class="nk-block-head nk-block-head">
        <div class="nk-block-between">
            <div class="nk-block-head-content d-flex">
                @if($title)
                <h3 class="nk-block-title page-title">{!! $title !!}</h3>
                @endif
                @include('datatables::default.includes.btn-filters')
            </div>
            <div class="nk-block-head-content">
                <div class="toggle-wrap nk-block-tools-toggle">
                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                    <div class="toggle-expand-content" data-content="pageMenu">
                        <ul class="nk-block-tools g-3">
                            @include('datatables::default.includes.create-button')
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($customFiltersView)
        @include($customFiltersView)
    @endif
    @endunless
    <div class="nk-block">
            @include('datatables::default.includes.offline')
            @include('datatables::default.includes.sorting-pills')
            @include('datatables::default.includes.filter-pills')
        <div class="card card-stretch">
            <div class="card-inner-group">
                <div class="card-inner position-relative card-tools-toggle">
                    <div class="card-title-group justify-content-end">
                        <div class="card-tools mr-n1">
                            <ul class="btn-toolbar gx-1">
                                <li class="btn-toolbar-sep"></li>
                                @include('datatables::default.includes.filters')
                                @include('datatables::default.includes.bulk-actions')
                            </ul>
                        </div>
                    </div>
                    <div class="card-search search-wrap active" style="max-width: 85%">
                        <div class="card-body">
                            <div class="search-content">
                                <input type="text" class="form-control border-transparent form-focus-none p-0"
                                    placeholder="Recherche" wire:model{{ $this->searchFilterOptions }}="filters.search">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-inner p-0 table-responsive">
                    @include('datatables::default.includes.table')
                </div>
                <div class="card-inner">
                    @include('datatables::default.includes.pagination')
                </div>
            </div>
        </div>
    </div>
</div>
