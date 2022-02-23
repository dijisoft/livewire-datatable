<?php

namespace Dijisoft\LivewireDatatable\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class DatatableExport implements FromCollection, WithHeadings
{
    use Exportable;

    public $collection;
    public $columns;

    public function __construct($collection, $columns=null)
    {
        $this->collection = collect($collection->toArray());
        $this->columns = collect($columns)
            ->map(fn($col) => $col->text)
            ->reject(fn($col) => strtolower($col) == 'actions')
            ->filter()
            ->toArray();

        if($columns) {
            $this->collection = $collection->map(
                fn($elem) => $elem->only(
                    collect($columns)
                        ->map(fn($col) => $col->column)
                        ->toArray()
                )
            );
        }
    }

    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return $this->columns?? array_keys((array) $this->collection->first());
    }

    public function download($name, $writerType) {
        return Excel::download($this, $name, $writerType);
    }
}
