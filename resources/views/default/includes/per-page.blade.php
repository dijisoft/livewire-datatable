@if ($paginationEnabled && !in_array('per-page', $hide))
    <div class="ms-3">
        <select
            wire:model="perPage"
            id="perPage"
            class="form-control form-control-sm"
        >
            @foreach ($perPageAccepted as $item)
                <option value="{{ $item }}">{{ $item === -1 ? __('All') : $item }}</option>
            @endforeach
        </select>
    </div>
@endif
