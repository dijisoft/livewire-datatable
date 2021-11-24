<?php

namespace Dijisoft\LivewireDatatable;

use Illuminate\Support\ServiceProvider;
use Blade;

class LivewireDatatableServiceProvider extends ServiceProvider {

    public function boot() {
        $this->loadViewsFrom(base_path('resources/views/vendor/livewire-datatable'), 'datatables');

        Blade::component('datatables::default.components.table.table', 'datatables::default.table');
        Blade::component('datatables::default.components.table.heading', 'datatables::default.table.heading');
        Blade::component('datatables::default.components.table.row', 'datatables::default.table.row');
        Blade::component('datatables::default.components.table.cell', 'datatables::default.table.cell');
    }
}