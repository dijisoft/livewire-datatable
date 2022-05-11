
@if(count($btnFilters??[]) && !in_array('btn-filters', $hide))
    @foreach ($btnFilters as $key => $filter)
        @if($filter->btnLayout == 'nav')
        <ul class="nav nav-tabs px-0 px-xxl-4"
            style="margin-top: -14px; margin-left: 5rem">
            @foreach($filter->options() as $optionKey => $optionValue)
                <li class="nav-item">
                    <a href='#{{ $optionValue['text']?? $optionValue }}' class="nav-link @if(Str::is($filters[$key]??'', $optionKey)) active @endif" @if(Str::is($filters[$key]??'', $optionKey))
                        wire:click="$set('filters.{{ $key }}', null)" @else wire:click="$set('filters.{{ $key }}', '{{ $optionKey }}')" @endif>
                        <span>{{ $optionValue['text']?? $optionValue }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        @else
            @foreach ($btnFilters as $key => $filter)
                <div class="btn-group btn-group-sm d-none d-sm-block @if($title) ms-4 @endif"> 
                @foreach($filter->options() as $optionKey => $optionValue)
                    <button type="button" class="btn btn-dim {{ $optionValue['class']??'' }} @if(Str::is($filters[$key]??'', $optionKey)) active @endif" 
                        @if(Str::is($filters[$key]??'', $optionKey)) 
                        wire:click="$set('filters.{{ $key }}', null)" 
                        @else 
                        wire:click="$set('filters.{{ $key }}', '{{ $optionKey }}')"
                        @endif>{{ $optionValue['text']?? $optionValue }}</button>
                @endforeach
                </div>
            @endforeach
        @endif
    @endforeach

    @foreach ($btnFilters as $key => $filter)
    <div class="dropdown d-sm-none @if($title) ms-4 @endif">
        <button class="dropdown-toggle btn btn-light btn-sm" data-bs-toggle="dropdown">
            <em class="icon ni ni-filter-alt me-1"></em> {{ ucfirst($key) }}
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <ul class="link-list-opt no-bdr">
                @foreach($filter->options() as $optionKey => $optionValue)
                <li><button type="button" class="btn-block btn btn-dim {{ $optionValue['class']??'' }} @if(Str::is($filters[$key]??'', $optionKey)) active @endif" @if(Str::is($filters[$key]??'',
                        $optionKey)) wire:click="$set('filters.{{ $key }}', null)" @else wire:click="$set('filters.{{ $key }}', '{{ $optionKey }}')"
                        @endif>{{ $optionValue['text']?? $optionValue }}</button></li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
@endif