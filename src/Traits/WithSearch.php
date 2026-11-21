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
            return '.live.debounce.' . $this->searchFilterDebounce . 'ms';
        }

        if ($this->searchFilterDefer) {
            return '';
        }

        if ($this->searchFilterLazy) {
            // Livewire 4: "blur" alone only syncs the client state; "live" sends the
            // request. On Livewire 3 both forms send the request on blur.
            return '.live.blur';
        }

        return '.live';
    }
}
