@foreach($columns as $column)
    @if ($column->isVisible())
        <div 
            class="{{ $column->class($row) }}"
            @if($column->triggersExpandable())
                x-on:click="open == {{ $row->id }}? open = 0 : open = {{ $row->id }}" style="cursor: pointer"
            @elseif($onRowClick && ! str(get_class($column))->endsWith('ActionsColumn'))
                x-on:click="{{ str($onRowClick)->replace('{id}', $row->id) }}"
            @elseif($url && ! str(get_class($column))->endsWith('ActionsColumn'))
                x-on:click="window.location='{{ $url }}'"
            @endif>
            @if (strlen($column->editable?? ''))
                @if ($editedIndex === $row->id && $editedField === $column->editable)
                    <div class="form-control-wrap" style="max-width: 18rem">
                        <div class="form-icon form-icon-right cursor-pointer" wire:click="resetEditable()">
                            <em class="icon ni ni-cross"></em>
                        </div>
                        <input type="text" class="form-control"
                            x-on:click.away="$wire.saveEditable()"
                            wire:keydown.enter="saveEditable()"
                            wire:model="editedValue" />
                        @if ($errors->any())
                            <div class="is-invalid">{{ $errors->first() }}</div>
                        @endif
                    </div>   
                @else
                    <div class="cursor-pointer" wire:click="editIndex({{ $row->id }}, '{{ $column->editable }}', '{{ $column->formatted($row) }}')">
                        {{ new \Illuminate\Support\HtmlString($column->formatted($row)) }}
                    </div>
                @endif
            @elseif ($column->asHtml)
                {{ new \Illuminate\Support\HtmlString($column->formatted($row)) }}
            @else
                {{ $column->formatted($row) }}
            @endif
        </div>
    @endif
@endforeach
