@if ($filtersView || count($filtersList))
<li>
    <div class="dropdown">
        <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
            @if (count($this->getFiltersWithoutSearch()) || count($daterangefilters))
            <div class="dot dot-primary"></div>
            @endif
            <em class="icon ni ni-filter-alt"></em>
        </a>
        <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
            <div class="dropdown-head">
                <span class="sub-title dropdown-title">Filtres</span>
            </div>
            <div class="dropdown-body dropdown-body-rg">
                <div class="row gx-6 gy-3">
                    @if ($filtersView)
                        @include($filtersView)
                    @elseif (count($filtersList))
                        @foreach ($filtersList as $key => $filter)
                        <div class="col-12">
                            <div wire:key="filter-{{ $key }}" class="form-group">
                                @if ($filter->isSelect())
                                <label for="filter-{{ $key }}" class="overline-title overline-title-alt">
                                    {{ $filter->name() }}
                                </label>
                                <select onclick="event.stopPropagation();" wire:model="filters.{{ $key }}" id="filter-{{ $key }}" class="form-control">
                                    @foreach($filter->options() as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @endif
                                @if ($filter->isDaterange())
                                <div wire:ignore>
                                    <label for="filter-{{ $key }}" class="overline-title overline-title-alt">
                                        {{ $filter->name() }}
                                    </label>
                                    <div class="form-control-wrap">
                                        <span class="btn btn-outline-light btn-block cp dtpicker" id="filter-{{ $key }}" data-model='{{ $key }}'
                                                data-toggle="tooltip" title="Sélectionner dates" data-placement="left">
                                            <span class="daterange-title" id="daterangepicker_title"></span>&nbsp;
                                            <span class="daterange-date" id="daterangepicker_date">Sélectionner dates</span>
                                            <em class="icon ni ni-calendar"></em>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @if (count($this->getFiltersWithoutSearch()) || count($daterangefilters))
            <div class="dropdown-foot between">
                <a class="clickable cp" wire:click.prevent="resetFilters">Réinitialiser</a>
            </div>
            @endif
        </div>
    </div>
</li>
@endif

@push('scripts')
<script>
    /** THIS IS NOT WORKING ON SUB COMPONENT, push('scripts') DOES NOT GET ECHOED */
    var initDtPicker = function() {
        $('.dtpicker').each(function () {
            var picker = $(this);
            
            function cb(start, end, label) {
                var range = start.format('MMM D') + ' - ' + end.format('MMM D');

                picker.find('#daterangepicker_date').html(range);

                @this.set('daterangefilters.' + picker.data('model'), {start: start.format('YYYY-MM-DD'), end: end.format('YYYY-MM-DD')});
            }

            picker.daterangepicker({
                opens: 'left',
                ranges: {
                    'Derniers 7 jours': [moment().subtract(6, 'days'), moment()],
                    'Derniers 30 jours': [moment().subtract(29, 'days'), moment()],
                    'Derniers 3 mois': [moment().subtract(3, 'month'), moment()],
                    'Ce Mois': [moment().startOf('month'), moment().endOf('month')],
                    'Mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);
        });
    };

    Livewire.on('initDtPicker', function() {
        initDtPicker();
    });
    
    document.addEventListener("livewire:load", () => {
        initDtPicker();
    });
</script>
@endpush
