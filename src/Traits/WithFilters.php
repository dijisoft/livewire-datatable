<?php

namespace Dijisoft\LivewireDatatable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Dijisoft\LivewireDatatable\Utilities\ColumnUtilities;
use Dijisoft\LivewireDatatable\Views\Column;
use Dijisoft\LivewireDatatable\Views\Filter;
use Carbon\Carbon;

/**
 * Trait WithFilters.
 */
trait WithFilters
{
    /**
     * Filter values
     *
     * @var array
     */
    public array $filters = [];
    public array $customFilters = [];
    public array $daterangefilters = [];
    public array $hiddenCustomFilters = [];

    /**
     * Map filter names
     *
     * @var array
     */
    public array $filterNames = [];

    /**
     * Show the filter dropdown
     *
     * @var bool
     */
    public bool $showFilterDropdown = true;

    /**
     * Default filters
     *
     * @var array|null[]
     */
    public array $baseFilters = [
        'search' => null,
    ];

    /**
     * Prebuild the $filters array
     */
    public function mountWithFilters(): void
    {
        $this->checkFilters();
        $this->customFilters = $this->initCustomFilters();
    }

     /**
     * Get filter definitions
     */
    public function getFilterDefinitionsProperty() {
        return $this->filters();
    }

    /**
     * Reset the filters array but keep the value for search
     */
    public function resetFilters(): void
    {
        $search = $this->filters['search'] ?? null;

        $this->reset('filters', 'daterangefilters');
        $this->customFilters = $this->initCustomFilters();

        $this->filters['search'] = $search;

        $this->checkFilters();
    }

    /**
     * Init the custom filters array
     */
    public function initCustomFilters(): array
    {
        return [];
    }

    /**
     * Runs when any filter is changed
     */
    public function updatedFilters(): void
    {
        // Remove the search filter when it's empty
        if (isset($this->filters['search']) && $this->filters['search'] === '') {
            $this->resetSearch();
        }

        // Remove any url params that are empty
        $this->checkFilters();

        // Reset the page when filters are changed
        $this->resetPage();
    }

    /**
     * Define the filters array
     *
     * @return Filter[]
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Removes any filters that are empty
     */
    public function checkFilters(): void
    {
        foreach ($this->filterDefinitions as $filter => $_default) {
            if (! isset($this->filters[$filter]) || $this->filters[$filter] === '') {
                $this->filters[$filter] = null;
                $this->filterNames[$filter] = $_default->name;
            }
        }
    }

    /**
     * Cleans $filter property of any values that don't exist
     * in the filter() definition.
     */
    public function cleanFilters(): void
    {
        // Filter $filters values
        $this->filters = collect($this->filters)->filter(function ($filterValue, $filterName) {
            $filterDefinitions = $this->filterDefinitions;

            // Ignore search
            if ($filterName === 'search') {
                return;
            }

            // Filter out any keys that weren't defined as a filter
            if (! isset($filterDefinitions[$filterName])) {
                return;
            }

            // Ignore null values
            if (is_null($filterValue)) {
                return;
            }

            // Handle 'select' filters
            if ($filterDefinitions[$filterName]->isSelect()) {
                foreach ($this->getFilterOptions($filterName) as $optionValue) {
                    // If the option is an integer, typecast filter value
                    if (is_int($optionValue) && $optionValue === (int)$filterValue) {
                        return;
                    }

                    // Strict check the value
                    if ($optionValue === $filterValue) {
                        return;
                    }
                }
            }

            return;
        })->toArray();
    }

    /**
     * Define the string location to a view to be included as the filters view
     *
     * @return string|null
     */
    public function filtersView(): ?string
    {
        return null;
    }

    /**
     * Check if a filter exists and isn't null
     *
     * @param  string  $filter
     *
     * @return bool
     */
    public function hasFilter(string $filter): bool
    {
        return isset($this->filters[$filter]) && $this->filters[$filter] !== null && $this->filters[$filter] !== '';
    }

    /**
     * Get the value of a given filter
     *
     * @param  string  $filter
     *
     * @return int|string|null
     */
    public function getFilter(string $filter)
    {
        if ($this->hasFilter($filter)) {
            if (in_array($filter, collect($this->filterDefinitions)->keys()->toArray(), true) && $this->filterDefinitions[$filter]->isSelect()) {
                return $this->hasIntegerKeys($filter) ? (int)$this->filters[$filter] : trim($this->filters[$filter]);
            }

            return trim($this->filters[$filter]);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return collect($this->filters)
            ->reject(fn ($value) => $value === null || $value === '')
            ->toArray();
    }

    /**
     * @return array
     */
    public function getFiltersWithoutSearch(): array
    {
        return collect($this->getFilters())
            ->reject(fn ($_value, $key) => $key === 'search')
            ->toArray();
    }

    /**
     * @return array
     */
    public function getFiltersWithoutBtn(): array
    {
        return collect($this->filterDefinitions)
            ->reject(fn($f) => $f->isBtn())
            ->toArray();
    }

    /**
     * @return array
     */
    public function getFiltersBtn(): array
    {
        return collect($this->filterDefinitions)
            ->reject(fn($f) => !$f->isBtn())
            ->toArray();
    }


    /**
     * Set a given filter to null
     *
     * @param $filter
     */
    public function removeFilter($filter): void
    {
        if (isset($this->filters[$filter])) {
            unset($this->filters[$filter]);
        }
       
        if (isset($this->daterangefilters[$filter])) {
            unset($this->daterangefilters[$filter]);
        }
        
        if (isset($this->customFilters[$filter])) {
            unset($this->customFilters[$filter]);
        }
    }

    /**
     * Get the options for a given filter if they exist
     *
     * @param  string  $filter
     *
     * @return array
     */
    public function getFilterOptions(string $filter): array
    {
        return collect($this->filterDefinitions[$filter]->options())
            ->keys()
            ->reject(fn ($item) => $item === '' || $item === null)
            ->values()
            ->toArray();
    }

    /**
     * Check whether the filter has numeric keys or not
     *
     * @param  string  $filter
     *
     * @return bool
     */
    public function hasIntegerKeys(string $filter): bool
    {
        return is_int($this->getFilterOptions($filter)[0] ?? null);
    }

    /**
     * Collects columns with $searchable = true
     *
     * @return Column[]
     */
    public function getSearchableColumns() : array
    {
        return array_filter($this->columns(), fn (Column $column) => $column->isSearchable());
    }

    /**
     * Collects columns with $searchable = true
     *
     * @return Column[]
     */
    public function getSearchableFields() : array
    {
        return array_filter($this->columns(), fn (Column $column) => $column->isSearchable());
    }

     /**
     * Gets the value of currently filtered column (useful for transforming the value in filter pills)
     */
    public function getFilterValue($key, $value): ?string
    {
        return $value;
    }

    /**
     * Apply Search Filter
     *
     * @param Builder|Relation $query
     * @return Builder|Relation
     */
    public function applyFilters($query)
    {
        $searchableColumns = $this->getSearchableColumns();

        if ($this->hasFilter('search') && count($searchableColumns)) {
            $search = $this->getFilter('search');

            // Group search conditions together
            $query->where(function (Builder $subQuery) use ($search, $query, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    // Does this column have an alias or relation?
                    $hasRelation = ColumnUtilities::hasRelation($column->column());

                    // Let's try to map this column to a selected column
                    $selectedColumn = ColumnUtilities::mapToSelected($column->column(), $query);

                    // If the column has a search callback, just use that
                    if ($column->hasSearchCallback()) {
                        // Call the callback
                        ($column->getSearchCallback())($subQuery, $search);
                    } elseif (! $hasRelation || $selectedColumn) { // If the column isn't a relation or if it was previously selected
                        $whereColumn = $selectedColumn ?? $column->column();

                        // TODO: Skip Aggregates
                        if (! $hasRelation) {
                            $whereColumn = $query->getModel()->getTable() . '.' . $whereColumn;
                        }

                        // We can use a simple where clause
                        $subQuery->orWhere($whereColumn, 'like', '%' . $search . '%');
                    } else {
                        // Parse the column
                        $relationName = ColumnUtilities::parseRelation($column->column());
                        $fieldName = ColumnUtilities::parseField($column->column());

                        // We use whereHas which can work with unselected relations
                        $subQuery->orWhereHas($relationName, function (Builder $hasQuery) use ($fieldName, $search) {
                            $hasQuery->where($fieldName, 'like', '%' . $search . '%');
                        });
                    }
                }
            });
        }

        foreach($this->getFiltersWithoutSearch() as $key => $value) {
            if($filter = ($this->filterDefinitions[$key] ?? null)) {
                if($filter->hasCallback()) {
                    $filter->getCallback()($query, $value);
                } else {
                    $query->where($key, $value);
                }
            }
        }
        
        foreach($this->daterangefilters as $key => $value) {
            if($filter = ($this->filterDefinitions[$key] ?? null)) {
                if($filter->hasCallback()) {
                    $filter->getCallback()($query, $value);
                } else {
                    $query->whereBetween($key, [
                        Carbon::parse($value['start'])->startOfDay(), 
                        Carbon::parse($value['end'])->endOfDay()
                    ]);
                }
            }
        }

        return $query;
    }
}