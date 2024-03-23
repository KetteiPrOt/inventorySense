@props(['danger' => false, 'hover' => true])

@php
    $classString = 
        $hover
        ? ($danger ? 'bg-red-100 hover:bg-red-200' : 'hover:bg-slate-100')
        : '';
@endphp

<tr
    {!! $attributes->merge(['class' => "$classString"]) !!}
>
    {{$slot}}
</tr>