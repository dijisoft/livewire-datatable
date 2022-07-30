<span 
    class="btn btn-outline-light btn-white btn-block" 
    id="filter-{{ $key }}"
    x-data
    x-ref="picker"
    x-init="() => {
        let picker = $($refs.picker);

        picker.daterangepicker({
            opens: 'left',
            ranges: {
                '@lang("Last 7 days")': [moment().subtract(6, 'days'), moment()],
                '@lang("Last 30 days")': [moment().subtract(29, 'days'), moment()],
                '@lang("Last 3 months")': [moment().subtract(3, 'month'), moment()],
                '@lang("Last month")': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                '@lang("This month")': [moment().startOf('month'), moment().endOf('month')]
            }
        }, function (start, end, label) {
            var range = start.format('MMM D') + ' - ' + end.format('MMM D');
            picker.find('#daterangepicker_date').html(range);
            @this.set('daterangefilters.{{ $key }}',  {
                start: start.format('YYYY-MM-DD'),
                end: end.format('YYYY-MM-DD')
            });
        });

        picker.on('cancel.daterangepicker', function () {
            @this.removeFilter('{{ $key }}');
            $(this).data('daterangepicker').setStartDate(moment().format('MM-DD-YYYY'))
            $(this).data('daterangepicker').setEndDate(moment().format('MM-DD-YYYY'));
        });
    }"
    data-bs-toggle="tooltip" 
    title="@lang('Select dates')"
    >
    <span class="daterange-title" id="daterangepicker_title"></span>&nbsp;
    <span class="daterange-date" id="daterangepicker_date">
        @if($daterangefilters[$key]?? false)
            <x-datatables::carbon :date="$daterangefilters[$key]['start']" format="M d"/> - 
            <x-datatables::carbon :date="$daterangefilters[$key]['end']" format="M d"/>
        @else
            @lang('Select dates')
        @endif
    </span>
    <em class="icon ni ni-calendar"></em>
</span>