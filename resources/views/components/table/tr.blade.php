@props(['danger' => false, 'alert' => false, 'hover' => true])

@php
    $classString = '';
    if($danger){
        $classString .= 'bg-red-100 ';
    } else if($alert){
        $classString .= 'bg-yellow-100 ';
    }
    if($hover){
        if($danger){
            $classString .= 'hover:bg-red-200 ';
        } else if($alert){
            $classString .= 'hover:bg-yellow-200 ';
        } else {
            $classString .= 'hover:bg-slate-100 ';
        }
    }
@endphp

<tr
    {!! $attributes->merge(['class' => "$classString"]) !!}
>
    {{$slot}}
</tr>
