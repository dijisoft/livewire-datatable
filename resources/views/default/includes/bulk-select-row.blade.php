@if (count($bulkActions) && !in_array('bulk-actions', $hide) && (($selectPage && $rows->total() > $rows->count()) || count($selected)))
    <x-datatables::default.table.row wire:key="row-message" style="display: table-caption; padding: 10px; border-bottom: 1px solid #dfe2ec">
        <x-datatables::default.table.cell colspan="{{ count($bulkActions) ? count($columns) + 1 : count($columns) }}">
            @if (count($selected) && !$selectAll && !$selectPage)
                <div>
                    <span>
                        @lang('You have selected')
                        <strong>{{ count($selected) }}</strong>
                        @lang(':rows', ['rows' => count($selected) === 1 ? __('row') : __('rows')]).
                    </span>

                    <button
                        wire:click="resetBulk"
                        wire:loading.attr="disabled"
                        type="button"
                        class="btn btn-primary btn-xs"
                    >
                        @lang('Unselect All')
                    </button>
                </div>
            @elseif ($selectAll)
                <div>
                    <span>
                        @lang('You are currently selecting all')
                        <strong>{{ number_format($rows->total()) }}</strong>
                        @lang('rows').
                    </span>

                    <button
                        wire:click="resetBulk"
                        wire:loading.attr="disabled"
                        type="button"
                        class="btn btn-primary btn-xs"
                    >
                        @lang('Unselect All')
                    </button>
                </div>
            @else
                @if ($rows->total() === count($selected))
                    <div>
                        <span>
                            @lang('You have selected')
                            <strong>{{ count($selected) }}</strong>
                            @lang(':rows', ['rows' => count($selected) === 1 ? __('row') : __('rows')]).
                        </span>

                        <button
                            wire:click="resetBulk"
                            wire:loading.attr="disabled"
                            type="button"
                            class="btn btn-primary btn-xs"
                        >
                            @lang('Unselect All')
                        </button>
                    </div>
                @else
                    <div>
                        <span>
                            @lang('You have selected')
                            <strong>{{ $rows->count() }}</strong>
                            @lang('rows'), @lang('do you want to select all')
                            <strong>{{ number_format($rows->total()) }}</strong>?
                        </span>

                        <button
                            wire:click="selectAll"
                            wire:loading.attr="disabled"
                            type="button"
                            class="btn btn-primary btn-xs"
                        >
                            @lang('Select All')
                        </button>

                        <button
                            wire:click="resetBulk"
                            wire:loading.attr="disabled"
                            type="button"
                            class="btn btn-primary btn-xs"
                        >
                            @lang('Unselect All')
                        </button>
                    </div>
                @endif
            @endif
        </x-datatables::default.table.cell>
    </x-datatables::default.table.row>
@endif
