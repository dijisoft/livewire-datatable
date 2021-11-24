@if ($paginationEnabled && $showPerPage)
    <div class="ml-3">
        <select
            wire:model="perPage"
            id="perPage"
            class="form-control form-control-sm"
        >
            @foreach ($perPageAccepted as $item)
                <option value="{{ $item }}">{{ $item === -1 ? __('All') : $item }}</option>
            @endforeach
        </select>
        {{-- <small class="ml-1 mt-1">Rangées</small> --}}
    </div>
@endif
