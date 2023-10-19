@if (($filtersView || count($filtersList)) && !in_array('filters', $hide))

    @foreach ($filtersList as $key => $filter)
        @continue(! $filter->isDropdown())
        <li>
            <div class="dropdown">
                <a href="#" 
                    class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white" 
                    data-bs-toggle="dropdown" aria-expanded="true">
                    {{ $filter->name() }}
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <ul class="link-list-opt no-bdr">
                        @foreach($filter->options() as $optionKey => $value)
                            <li>
                                <a 
                                    id="filter-{{ $optionKey?: 0 }}" 
                                    wire:click="$set('filters.{{ $key }}', '{{ $optionKey }}')"
                                >
                                    <span>{{ $value }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </li>
    @endforeach

    @foreach ($filtersList as $key => $filter)
        @continue(! $filter->isDaterange())
        <li>
            <div class="form-control-wrap">
                <x-datatables::daterangepicker :key="$key" :daterangefilters="$daterangefilters" />
            </div>
        </li>
    @endforeach

    @if(collect($filtersList)->whereNotIn('type', ['daterange', 'dropdown'])->count())
        <li x-data>
            <div class="dropdown" wire:key='datatable-filters-{{ $this->id }}'>
                <a 
                    href="#" 
                    class="btn btn-trigger btn-icon dropdown-toggle" 
                    data-bs-toggle="dropdown"
                    x-ref="dropdownToggle" 
                    wire:ignore.self
                >
                    @if (count($this->getFiltersWithoutSearch()))
                    <div class="dot dot-primary"></div>
                    @endif
                    <em class="icon ni ni-filter-alt"></em>
                </a>
                <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right" 
                    wire:ignore.self  
                    onclick="event.stopPropagation();"
                >
                    <div class="dropdown-head pr-1">
                        <span class="sub-title dropdown-title">@lang('Filters')</span>
                        <button class="btn btn-sm" x-on:click="Bootstrap.Dropdown.getInstance($refs.dropdownToggle).toggle()"><em class="icon ni ni-cross-sm"></em></button>
                    </div>
                    <div class="dropdown-body dropdown-body-rg">
                        <div class="gx-6 gy-3">
                            @if ($filtersView)
                                @include($filtersView)
                            @elseif (count($filtersList))
                                @foreach ($filtersList as $key => $filter)
                                    @continue($filter->isDaterange() || $filter->isDropdown())
                                    <div 
                                        wire:key="filter-{{ $key }}" 
                                        class="form-group"
                                    >
                                        @if ($filter->isSelect())
                                        <label for="filter-{{ $key }}" class="overline-title overline-title-alt">
                                            {{ $filter->name() }}
                                        </label>
                                        <div class="form-control-wrap">
                                            <x-datatables::select2 id="filter-{{ $key }}" wire:model="filters.{{ $key }}">
                                                <option value=""></option>
                                                @foreach($filter->options() as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </x-datatables::select2>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="dropdown-foot row">
                        <div class="col-sm-6 d-flex justify-content-start">
                            <a 
                                @class([
                                    'clickable cursor-pointer', 
                                    'd-none' => empty($this->getFiltersWithoutSearch()) && empty($daterangefilters)
                                ])
                                wire:click.prevent="resetFilters"
                            >
                                @lang('Reset')
                            </a>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-end">
                            <button class="btn btn-sm" x-on:click="Bootstrap.Dropdown.getInstance($refs.dropdownToggle).toggle()">
                                @lang('Close')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    @endif

@endif