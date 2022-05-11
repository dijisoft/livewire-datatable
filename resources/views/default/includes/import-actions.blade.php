@if (count($importActions) && !in_array('import-actions', $hide))
<li>
    <div class="dropdown mb-3 mb-md-0 d-block d-md-inline">
        <button class="btn btn-trigger btn-icon dropdown-toggle" type="button" id="importActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <em class="icon ni ni-upload-cloud"></em>
        </button>

        <div class="dropdown-menu dropdown-menu-right w-100" aria-labelledby="importActions">
            @foreach($importActions as $action => $title)
                <a
                    x-data x-on:click="$wire.emit('{{ $action }}')"
                    wire:key="import-action-{{ $action }}"
                    class="dropdown-item cp"
                >
                    {{ $title }}
                </a>
            @endforeach
        </div>
    </div>
</li>
@endif
