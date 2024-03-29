@props(['id', 'status' => false])

<div
    {!! $attributes->merge(['class' => 'flex items-center']) !!}
>
    <div
        class="w-5 h-0.5 bg-black"
    ></div>
    <button
        id="{{$id}}"
        class="
            {{$status ? 'permission-btn-enabled' : 'permission-btn-disabled'}}
            p-2 border-2 border-black rounded-full
        "
    >{{$slot}}</button>
</div>
