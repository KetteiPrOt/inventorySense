@props(['data'])
@php
    extract($data);
    $inputs = request()->all();
    $inputs['column'] = $column;
    $inputs['order'] = match($order){'desc' => 'asc', 'asc' => 'desc'};
@endphp

<a
    href="{{
        route($route, $inputs)
    }}"
>
    {{$slot}}
    @if($column == $currentColumn)
        @if($order == 'asc')
            <x-icons.order.ascending
                class="inline w-5 h-5"
            />
        @else
            <x-icons.order.descending
                class="inline w-5 h-5"
            />
        @endif
    @else
        <x-icons.order.undefined
            class="inline w-5 h-5"
        />
    @endif
</a>
