<?php

namespace Dijisoft\LivewireDatatable\Traits;

/**
 * Trait WithSearch.
 */
trait WithSearch
{
    /**
     * @var int|null
     */
    public ?int $searchFilterDebounce = 200;

    /**
     * @var bool|null
     */
    public ?bool $searchFilterDefer = null;

    /**
     * @var bool|null
     */
    public ?bool $searchFilterLazy = null;

    
    public function getShowSearchProperty() {
        return collect($this->columns())->where('searchable', true)->count();
    }

    /**
     * Clear the search filter specifically
     */
    public function resetSearch(): void
    {
        $this->filters['search'] = null;
    }

    /**
     * Build Livewire model options for the search input
     *
     * @return string
     */
    public function getSearchFilterOptionsProperty(): string
    {
        if ($this->searchFilterDebounce) {
            return '.debounce.' . $this->searchFilterDebounce . 'ms';
        }

        if ($this->searchFilterDefer) {
            return '.defer';
        }

        if ($this->searchFilterLazy) {
            return '.lazy';
        }

        return '';
    }
}
