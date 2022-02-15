<?php

namespace Dijisoft\LivewireDatatable\Views;

use \Carbon\Carbon;

class DateTimeColumn extends Column
{
    public function __construct(string $text = null, string $column = null)
    {
        parent::__construct($text, $column);

        $this->format(fn($value) => $value ? Carbon::parse($value)->format(config('livewire-datatable.datetime_format', 'd/m/Y H:i')) : null);
    }
}
