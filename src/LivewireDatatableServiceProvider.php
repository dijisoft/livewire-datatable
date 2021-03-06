<?php

namespace Dijisoft\LivewireDatatable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Blade;

class LivewireDatatableServiceProvider extends ServiceProvider {

    public function boot() {

        $this->publishes([
            __DIR__.'/../config' => resource_path('views/config'),
        ], 'livewire-datatable-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/datatables'),
        ], 'livewire-datatable-views');

        $this->publishes([
            __DIR__ . '/../app/Providers' => base_path('app/Providers'),
        ], 'livewire-datatable-provider');

        if (!$this->app->runningInConsole()) {
            Collection::macro('total', fn() => $this->count());

            $this->loadViewsFrom(__DIR__.'/../resources/views', 'datatables');

            Blade::component('datatables::default.components.table.table', 'datatables::default.table');
            Blade::component('datatables::default.components.table.heading', 'datatables::default.table.heading');
            Blade::component('datatables::default.components.table.row', 'datatables::default.table.row');
            Blade::component('datatables::default.components.table.expandable-row', 'datatables::default.table.expandable-row');
            Blade::component('datatables::default.components.table.cell', 'datatables::default.table.cell');
        }
    }
}