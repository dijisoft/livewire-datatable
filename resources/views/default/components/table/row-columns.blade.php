@foreach($columns as $column)
    @if ($column->isVisible())
        <div class="{{ $column->class($row) }}">
            @if (strlen($column->editable))
                @if ($editedIndex === $row->id && $editedField === $column->editable)
                    <div class="form-control-wrap" style="max-width: 18rem">
                        <div class="form-icon form-icon-right cp" wire:click="resetEditable()">
                            <em class="icon ni ni-cross"></em>
                        </div>
                        <input type="text" class="form-control"
                            x-on:click.away="$wire.saveEditable()"
                            wire:keydown.enter="saveEditable()"
                            wire:model.defer="editedValue" />
                        @if ($errors->any())
                            <div class="is-invalid">{{ $errors->first() }}</div>
                        @endif
                    </div>   
                @else
                    <div class="cp" wire:click="editIndex({{ $row->id }}, '{{ $column->editable }}', '{{ $column->formatted($row) }}')">
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
