<x-datatables::default.table
    :styles="$styles">
    <x-slot name="head">
        @if (count($bulkActions))
            <div class="nk-tb-col nk-tb-col-check">
                <div class="custom-control custom-control-sm custom-checkbox notext">
                    <input type="checkbox" class="custom-control-input" wire:model="selectPage" id="checkAll">
                    <label class="custom-control-label" for="checkAll"></label>
                </div>
            </div>
        @endif

        @foreach($columns as $column)
            @if ($column->isVisible())
                @if ($column->isBlank())
                    <x-datatables::default.table.heading />
                @else
                    <x-datatables::default.table.heading
                        :sortable="$column->isSortable()"
                        :column="$column->column()"
                        :direction="$column->column() ? $sorts[$column->column()] ?? null : null"
                        :text="$column->text() ?? ''"
                        :class="$column->class() ?? ''"
                    />
                @endif
            @endif
        @endforeach
    </x-slot>

    
    <x-slot name="body">
        @include('datatables::default.includes.bulk-select-row')

        @forelse ($rows as $index => $row)
            <x-datatables::default.table.row
                wire:loading.class.delay="text-muted"
                wire:key="table-row-{{ $row->getKey() }}"
                :url="method_exists($this, 'getTableRowUrl') ? $this->getTableRowUrl($row) : null"
            >
                @if (count($bulkActions))
                <div class="nk-tb-col nk-tb-col-check">
                    <div class="custom-control custom-control-sm custom-checkbox notext">
                        <input type="checkbox" class="custom-control-input"
                            wire:model="selected"
                            value="{{ $row->{$primaryKey} }}"
                            type="checkbox" id="check{{ $row->{$primaryKey} }}"
                        />
                        <label class="custom-control-label" for="check{{ $row->{$primaryKey} }}"></label>
                    </div>
                </div>
                @endif

                @include($rowView, ['row' => $row])
            </x-datatables::default.table.row>
        @empty
            <x-datatables::default.table.row>
                <x-datatables::default.table.cell colspan="{{ count($bulkActions) ? count($columns) + 1 : count($columns) }}">
                    <div class="p-2" wire:loading.remove>
                        @lang($emptyMessage)
                    </div>
                    {{-- <div class="d-flex justify-content-center py-4 ml-4" wire:loading  wire:target="rows">
                        <div class="spinner-border text-light" role="status">  
                            <span class="sr-only">Chargement...</span>
                        </div>
                    </div>     --}}
                </x-datatables::default.table.cell>
            </x-datatables::default.table.row>
           
        @endforelse
    </x-slot>
</x-datatables::default.table>
