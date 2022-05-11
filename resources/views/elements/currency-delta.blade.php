<span @class([
    'tb-sub',
    'text-success' => $value > 0,
    'text-danger' => $value < 0
])>
{!! $value > 0 ? '+' : '-' !!}
{{ $value? number_format($value, 0, '', ' ') : '-' }} <span>&euro;</span>
</span>