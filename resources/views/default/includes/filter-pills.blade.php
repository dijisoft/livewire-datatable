{{-- {{ json_encode($this->filters()) }} --}}
@if (!in_array('filter-pills', $hide) && (count($this->getFiltersWithoutSearch()) || count(array_filter($daterangefilters))) || count(array_filter($customFilters)))
    <div id="filterPills" class="mb-3">
        <small>@lang('Applied Filters'):</small>

        @foreach($filters as $key => $value)
            @if ($key !== 'search' && strlen($value))
                <span
                    wire:key="filter-pill-{{ $key }}"
                    class="badge badge-pill badge-info d-inline-flex align-items-center"
                >
                    {{ $filterNames[$key] ?? collect($this->columns())->pluck('text', 'column')->get($key, ucwords(strtr($key, ['_' => ' ', '-' => ' ']))) }}:
                    @php $filterObj = $this->filters()[$key]??null; @endphp
                    @if($filterObj && method_exists($filterObj, 'options'))
                        @if($filterObj->type == 'btn') 
                        {{ $filterObj->options()[$value]['text'] ?? $value }}
                        @else
                        {{ $filterObj->options()[$value] ?? $value }}
                        @endif
                    @else
                        {{ ucwords(strtr($this->getFilterValue($key, $value), ['_' => ' ', '-' => ' '])) }}
                    @endif

                    <a  x-data x-on:click="$wire.removeFilter('{{ $key }}')"
                        class="text-white ml-2 cp"
                    >
                        <span class="sr-only">@lang('Remove filter option')</span>
                        <svg style="width:.5em;height:.5em" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                        </svg>
                    </a>
                </span>
            @endif
        @endforeach

        @foreach($daterangefilters as $key => $value)
            @if ($value && ($value['start']??null) && ($value['end']??null))
                <span
                    wire:key="filter-pill-{{ $key }}"
                    class="badge badge-pill badge-info d-inline-flex align-items-center"
                >
                    {{ $filterNames[$key]?? $key }} @lang('from') 
                    {{ \Carbon\Carbon::parse($value['start'])->format('d/m/Y') }} @lang('to') {{ \Carbon\Carbon::parse($value['end'])->format('d/m/Y') }}
                    <a
                        wire:click.prevent="removeFilter('{{ $key }}')"
                        class="text-white ml-2 cp"
                    >
                        <span class="sr-only">@lang('Remove filter option')</span>
                        <svg style="width:.5em;height:.5em" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                        </svg>
                    </a>
                </span>
            @endif
        @endforeach


        @foreach($customFilters as $key => $value)
            @continue(in_array($key, $this->hiddenCustomFilters) || empty($value))
            
            @php 
                $value = $this->getFilterValue($key, $value)
            @endphp
            @if ($key !== 'search' && strlen($value))
                <span
                    wire:key="filter-pill-{{ $key }}"
                    class="badge badge-pill badge-info d-inline-flex align-items-center"
                >
                    {{ ucfirst($filterNames[$key]?? $key) }} : {{ $value }} 
                    <a
                        wire:click.prevent="removeFilter('{{ $key }}')"
                        class="text-white ml-2 cp"
                    >
                        <span class="sr-only">@lang('Remove filter option')</span>
                        <svg style="width:.5em;height:.5em" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                        </svg>
                    </a>
                </span>
            @endif
        @endforeach

        <span class="badge badge-pill badge-light"></span>
        <a
            x-data x-on:click="$wire.resetFilters"
            class="badge badge-pill badge-light cp"
        >
            @lang('Clear')
        </a>
    </div>
@endif
