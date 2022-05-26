<?php

namespace Dijisoft\LivewireDatatable\Traits;

/**
 * Trait WithHighlight.
 */
trait WithExpandableRows
{
    public string $expandedRowView = '';

    public function expandRows(string $view)
    {
        return $this->expandedRowView = $view; 
    }

    public function isExpandable()
    {
        return boolval($this->expandedRowView);
    }
}