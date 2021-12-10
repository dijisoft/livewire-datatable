<?php

namespace Dijisoft\LivewireDatatable\Views;

use Illuminate\Support\Carbon;

class DateTimeColumn extends Column
{
    public function __construct(string $text = null, string $column = null)
    {
        parent::__construct($text, $column);

        $this->format(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y H:i') : null);
    }
}
