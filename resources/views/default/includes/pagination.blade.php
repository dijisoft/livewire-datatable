@if($this->can_load && !in_array('pagination', $hide))
    @if ($showPagination)
    <div class="my-2">
        @if ($paginationEnabled && $rows->lastPage() > 1)
            <div class="row mx-0">
                <div class="col-12 col-md-6 d-flex">
                    {{ $rows->links() }}
                </div>

                <div class="col-12 col-md-6 text-center text-md-right text-muted d-flex justify-content-end mt-3 mt-sm-0">
                    <div>
                        <span>
                        @lang('Showing rows')
                        </span>
                        <strong>{{ $rows->count() ? $rows->firstItem() : 0 }}</strong>
                        <span>
                        @lang('to')
                        </span>
                        <strong>{{ $rows->count() ? $rows->lastItem() : 0 }}</strong>
                        <span>
                        @lang('of')
                        </span>
                        <strong>{{ $rows->total() }}</strong>
                        @lang('results')
                    </div>
                    @include('datatables::default.includes.per-page')</span>
                </div>
            </div>
        @else
            <div class="row mx-0">
                <div class="col-12 text-muted d-flex justify-content-start">
                    <div>
                        @lang('Showing')
                        <strong>{{ $rows->count() }}</strong>
                        @lang('results')
                    </div>
                    @include('datatables::default.includes.per-page')
                </div>
            </div>
        @endif
    </div>
    @endif
@endif
