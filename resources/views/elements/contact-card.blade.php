<span class="tb-lead">{{ $row->name_and_surname }}</span>
<span class="sub-text">{{ $row->email }}</span>
@if($row->any_tel)
<span class="sub-text">{{ $row->any_tel }}</span>
@endif

