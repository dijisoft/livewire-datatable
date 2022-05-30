@if (($filtersView || count($filtersList)) && !in_array('filters', $hide))
    @if(collect($filtersList)->where('type', '!=', 'daterange')->count())
        <li x-data>
            <div class="dropdown" wire:key='datatable-filters-{{ $this->id }}' x-ref="dropdown" wire:ignore.self>
                <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-bs-toggle="dropdown">
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
                        <button class="btn btn-sm" x-on:click="$($refs.dropdown).dropdown('toggle')"><em class="icon ni ni-cross-sm"></em></button>
                    </div>
                    <div class="dropdown-body dropdown-body-rg">
                        <div class="row gx-6 gy-3">
                            @if ($filtersView)
                                @include($filtersView)
                            @elseif (count($filtersList))
                                @foreach ($filtersList as $key => $filter)
                                    @continue($filter->isDaterange())
                                    <div class="col-12">
                                        <div wire:key="filter-{{ $key }}" class="form-group">
                                            @if ($filter->isSelect())
                                            <label for="filter-{{ $key }}" class="overline-title overline-title-alt">
                                                {{ $filter->name() }}
                                            </label>
                                            <div class="form-control-wrap">
                                                <x-datatables::select2 id="filter-{{ $key }}" wire:model="filters.{{ $key }}">
                                                    @foreach($filter->options() as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </x-datatables::select2>
                                            </div>
                                            @endif
                                            @if ($filter->isDaterange())
                                            <label for="filter-{{ $key }}" class="overline-title overline-title-alt">
                                                {{ $filter->name() }}
                                            </label>
                                            <div class="form-control-wrap">
                                                <x-datatables::daterangepicker :key="$key" :daterangefilters="$daterangefilters" />
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="dropdown-foot row">
                        <div class="col-sm-6 d-flex justify-content-start">
                            <a 
                                @class([
                                    'clickable cp', 
                                    'd-none' => empty($this->getFiltersWithoutSearch()) && empty($daterangefilters)
                                ])
                                wire:click.prevent="resetFilters"
                            >
                                @lang('Reset')
                            </a>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-end">
                            <button class="btn btn-sm" x-on:click="$($refs.dropdown).dropdown('toggle')">
                                @lang('Close')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    @endif
    @foreach ($filtersList as $key => $filter)
        @continue(! $filter->isDaterange())
        <li>
            <div class="form-control-wrap">
                <x-datatables::daterangepicker :key="$key" :daterangefilters="$daterangefilters" />
            </div>
        </li>
    @endforeach
@endif