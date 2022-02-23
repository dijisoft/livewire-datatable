<?php

namespace Dijisoft\LivewireDatatable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Trait WithSorting.
 */
trait WithSorting
{
    public bool $singleColumnSorting = false;
    public array $sorts = [];
    public array $sortNames = [];
    public array $sortDirectionNames = [];

    public function sortBy(string $field): ?string
    {
        $this->resetSortsExcept($field);
        
        if ($this->singleColumnSorting && count($this->sorts) && ! isset($this->sorts[$field])) {
            $this->sorts = [];
        }

        if (! isset($this->sorts[$field])) {
            return $this->sorts[$field] = 'asc';
        }

        if ($this->sorts[$field] === 'asc') {
            return $this->sorts[$field] = 'desc';
        }

        if ($this->sorts[$field] === 'desc') {
            return $this->sorts[$field] = 'asc';
        }

        unset($this->sorts[$field]);

        return null;
    }

    /**
     * @param  Builder|Relation  $query
     *
     * @return Builder|Relation
     */
    public function applySorting($query)
    {
        foreach ($this->sorts as $field => $direction) {
            if (optional($this->getColumn($field))->hasSortCallback()) {
                $query = app()->call($this->getColumn($field)->getSortCallback(), ['query' => $query, 'direction' => $direction]);
            } else {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }

    public function removeSort(string $field): void
    {
        if (isset($this->sorts[$field])) {
            unset($this->sorts[$field]);
        }
    }

    public function resetSortsExcept(string $field)
    {
        foreach (array_keys($this->sorts) as $key) {
            if($field == $key) continue;
            unset($this->sorts[$key]);
        }
    }

    public function resetSorts(): void
    {
        $this->sorts = [];
    }
}
