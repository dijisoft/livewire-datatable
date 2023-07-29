@if (!in_array('sorting-pills', $hide))
    <div class="mb-3 @if(empty($sorts)) d-none @endif">
        <small>@lang('Applied Sorting'):</small>

        @foreach($sorts as $col => $dir)
            <span
                wire:key="sorting-pill-{{ $col }}"
                class="badge badge-pill bg-info d-inline-flex align-items-center"
            >
                <span>{{ $sortNames[$col] ?? collect($this->columns())->pluck('text', 'column')->get($col, ucwords(strtr($col, ['_' => ' ', '-' => ' ']))) }}: {{ $dir === 'asc' ? ($sortDirectionNames[$col]['asc'] ?? 'A-Z') : ($sortDirectionNames[$col]['desc'] ?? 'Z-A') }}</span>

                <a
                    href="#"
                    wire:click="removeSort('{{ $col }}')"
                    class="text-white ms-2"
                >
                    <span class="visually-hidden">@lang('Remove sort option')</span>
                    <svg style="width:.5em;height:.5em" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                </a>
            </span>
        @endforeach

        <a
            href="#"
            wire:click="resetSorts"
            class="badge badge-pill bg-light"
        >
            @lang('Clear')
        </a>
    </div>
@endif
