@if (count($bulkActions) && !in_array('bulk-actions', $hide) && (($selectPage && $rows->total() > $rows->count()) || count($selected)))
<li>
    <div class="dropdown mb-3 mb-md-0 d-block d-md-inline">
        <button class="btn btn-trigger btn-icon dropdown-toggle" type="button" id="bulkActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <em class="icon ni ni-setting"></em>
            <div class="dot dot-primary"></div>
        </button>

        <div class="dropdown-menu dropdown-menu-right w-100" aria-labelledby="bulkActions">
            <ul class="link-check">
                @foreach($bulkActions as $action => $title)
                <li>
                    <a href="#" wire:click.prevent="{{ $action }}" wire:key="bulk-action-{{ $action }}">
                        {{ $title }}
                    </a>
                </li>
                @endforeach
            </ul>
           
        </div>
    </div>
</li>
@endif
