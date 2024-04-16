@props([
    'buttonId' => null,
    'status' => false,
    'rightConnection' => false,
    'leftConnection' => false,
    'visualLayout' => false
])

@php
    $classString = 'max-w-48 overflow-hidden flex items-center ';
    if($visualLayout){
        $classString .= 'bg-orange-400 ';
    }
@endphp

<div
    {!! $attributes->merge(['class' => $classString]) !!}
>
    @if($leftConnection)
        <div
            class="w-5 h-0.5 bg-black shrink-0"
        ></div>
    @endif
    <button
        @if(!is_null($buttonId))
            id="{{$buttonId}}"
        @endif
        class="
            {{$status ? 'permission-btn-enabled' : 'permission-btn-disabled'}}
            text-nowrap p-2 border-2 border-black rounded-full
        "
    >{{$slot}}</button>
    @if($rightConnection)
        <div
            class="w-64 h-0.5 bg-black"
        ></div>
    @endif
</div>
