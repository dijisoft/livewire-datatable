@if ($this->showSearch)
<li>
    <div class="form-control-wrap">
        <div class="form-icon form-icon-right" wire:click="$set('filters.search', null)">
            @if(strlen($filters['search']??''))
            <em class="icon ni ni-cross cp"></em>
            @else
            <em class="icon ni ni-search"></em>
            @endif
        </div>
        <input type="text" class="form-control" placeholder="{{ $searchFilterPlaceholder?? 'Recherche' }}"
            wire:model{{ $this->searchFilterOptions }}="filters.search" autocomplete="off">
    </div>
</li>
@endif
