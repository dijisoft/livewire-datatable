<a href="{{ $value->full_url?? $value->url?? '' }}" class="{{ $class??'' }}">
    {{ $value->name?? $row->name_and_surname?? $row->name?? '' }}
</a>