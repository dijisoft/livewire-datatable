<x-datatables::default.table
    :styles="$styles" :hide="$hide">
    <x-slot name="head">

        @if($this->isExpandable())
        <div class="nk-tb-col nk-tb-col-caret"></div>
        @endif

        @if (count($bulkActions))
            <div class="nk-tb-col nk-tb-col-check">
                <div class="custom-control custom-control-sm custom-checkbox notext">
                    <input type="checkbox" class="custom-control-input" wire:model="selectPage" id="checkAll-{{ $this->id }}">
                    <label class="custom-control-label" for="checkAll-{{ $this->id }}"></label>
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
                        :text="$column->textHtml() ?? $column->text() ?? ''"
                        :class="$column->headerClass() ?? ''"
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
                x-bind:class="open == {{ $row->id }}? 'parent-expanded' : ''"
                :url="method_exists($this, 'getTableRowUrl') ? $this->getTableRowUrl($row) : null"
            >
                @if($this->isExpandable())
                <div class="nk-tb-col nk-tb-col-caret" x-on:click="open == {{ $row->id }}? open = 0 : open = {{ $row->id }}" style="cursor: pointer">
                    <em class="icon text-primary ni" 
                        :class="open == {{ $row->id }}? 'ni-caret-down-fill' : 'ni-caret-right-fill'"></em>
                </div>
                @endif

                @if (count($bulkActions))
                <div class="nk-tb-col nk-tb-col-check">
                    <div class="custom-control custom-control-sm custom-checkbox notext">
                        <input type="checkbox" class="custom-control-input"
                            wire:model="selected"
                            value="{{ $row->{$primaryKey} }}"
                            type="checkbox" id="check-{{ $this->id . '-' . $row->{$primaryKey} }}"
                        />
                        <label class="custom-control-label" for="check-{{ $this->id . '-' . $row->{$primaryKey} }}"></label>
                    </div>
                </div>
                @endif

                @include($rowView, ['row' => $row])
              
            </x-datatables::default.table.row>

            @if($this->isExpandable())
            <x-datatables::default.table.expandable-row 
                wire:key="table-expanded-row-{{ $row->getKey() }}" 
                x-bind:class="open == {{ $row->id }}? 'expanded' : ''"
            >
                @include($this->expandedRowView, ['row' => $row])
            </x-datatables::default.table.expandable-row>
            @endif

        @empty
            <x-datatables::default.table.row>
                <x-datatables::default.table.cell colspan="{{ count($bulkActions) ? count($columns) + 1 : count($columns) }}">
                    <div class="p-2" wire:loading.remove>
                        @lang($emptyMessage)
                    </div>
                    <div class="spinner mt-4 mb-3 ms-2" role="status" wire:loading>  
                        <span class="visually-hidden">@lang('Loading')...</span>
                    </div>
                </x-datatables::default.table.cell>
            </x-datatables::default.table.row>
        @endforelse
    </x-slot>
</x-datatables::default.table>
