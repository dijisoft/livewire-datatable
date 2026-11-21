<?php

namespace Dijisoft\LivewireDatatable\Traits;

use Livewire\WithPagination;

/**
 * Trait WithCustomPagination.
 *
 * Livewire keeps the current page in $paginators and in the URL (?page=2).
 * This trait only makes the table's own page name the default one, so that
 * resetPage() and the pagination view's gotoPage(2) act on the right paginator.
 */
trait WithCustomPagination
{
    use WithPagination;

    public function pageName(): string
    {
        return property_exists($this, 'pageName') ? $this->pageName : 'page';
    }

    public function previousPage($pageName = null): void
    {
        $pageName ??= $this->pageName();

        $this->setPage(max($this->getPage($pageName) - 1, 1), $pageName);
    }

    public function nextPage($pageName = null): void
    {
        $pageName ??= $this->pageName();

        $this->setPage($this->getPage($pageName) + 1, $pageName);
    }

    public function gotoPage($page, $pageName = null): void
    {
        $this->setPage($page, $pageName ?? $this->pageName());
    }

    public function resetPage($pageName = null): void
    {
        $this->setPage(1, $pageName ?? $this->pageName());
    }
}
