@if ($create)
    @unless(auth()->check() && $createPermissions && !auth()->user()->canAny(array_filter(explode('|', $createPermissions))))
    <li class="nk-block-tools-opt">
        @if(is_array($create))
            <button wire:click="${{ $create['action']??'' }}" class="btn btn-icon btn-primary d-md-none">
                <em class="icon ni ni-plus"></em></button>
            <button wire:click="${{ $create['action']??'' }}" class="btn btn-primary d-none d-md-inline-flex">
                <em class="icon ni ni-plus"></em><span>Ajouter</span></button>
                
            @if($create['component']??false)
                @livewire($create['component']['name'], $create['component']['params']??[])
            @endif
        @else
            <a href="{{ route($create) }}" class="btn btn-icon btn-primary d-md-none">
                <em class="icon ni ni-plus"></em></a>
            <a href="{{ route($create) }}" class="btn btn-primary d-none d-md-inline-flex">
                <em class="icon ni ni-plus"></em><span>Ajouter</span></a>
        @endif
    </li>
    @endunless
@endif
