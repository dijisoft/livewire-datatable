<div
    wire:ignore
    x-data="{
        model: @entangle($attributes->wire('model')).live,
    }"
    x-init="
        select2 = $($refs.select)
            .not('.select2-hidden-accessible')
            .select2({
                dropdownAutoWidth: true,
                width: '100%',
                minimumResultsForSearch: -1
            });
        select2.on('select2:select', (event) => {
            if (event.target.hasAttribute('multiple')) { 
                model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); 
            } else { 
                model = event.target.value;
            }
        });
        select2.on('select2:unselect', (event) => {
            if (event.target.hasAttribute('multiple')) {
                model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); 
            } else { 
                model = null;
            }
        });
        $watch('model', (value) => {
            select2.val(value).trigger('change');
        });
    "
>
    <select x-ref="select" {{ $attributes->merge(['class' => 'form-control']) }}>
        {{ $slot }}
    </select>
</div>