<?php

namespace Dijisoft\LivewireDatatable\Views;

class BooleanColumn extends Column
{
    public function __construct(string $text = null, string $column = null)
    {
        parent::__construct($text, $column);

        $this->view('badge-boolean');
    }
}
