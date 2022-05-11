
<ul class="nk-tb-actions gx-1">
    @foreach($actions['quickActions']??[] as $action)
    <li class="nk-tb-action-hidden">
        <a 
            @if($action['click']??false) wire:click="{{ Str::replace('(id)', "($row->id)", $action['click']) }}" @else
            href="{{ route($action['route']??'', $row->id) }}" @endif
            class="btn btn-icon btn-trigger btn-tooltip {{ $action['class']??'' }}" data-bs-toggle="tooltip" data-placement="top" 
            data-original-title="{{ $action['title']??'' }}">
            <em class="icon ni ni-{{ $action['icon']??'' }}"></em>
        </a>
    </li>
    @endforeach
    @if($actions['dropdown']??false)
    <li>
        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown" aria-expanded="false">
            <em class="icon ni ni-more-h"></em>
        </a>
        <div class="dropdown-menu dropdown-menu-right" style="">
            <ul class="link-list-opt no-bdr">
                @foreach($actions['actions']??[] as $action)
                    @if($action['divider']??false) 
                        <li class="divider"></li>
                    @else
                        <li><a
                            @if($action['click']??false) wire:click="{{ Str::replace('(id)', "($row->id)", $action['click']) }}" @else
                            href="{{ route($action['route']??'', $row->id) }}" @endif
                            class="{{ $action['class']??'' }}">
                            <em class="icon ni ni-{{ $action['icon']??'' }}"></em><span>{{ $action['title']??'' }}</span></a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </li>
    @endif
</ul>
