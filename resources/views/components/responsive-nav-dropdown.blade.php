@props(['tag', 'align' => 'right', 'width' => 48, 'active' => false])

@php
    $classes = $active
        ? "
            flex items-center
            w-full ps-3 pe-4 py-2
            border-l-4 border-indigo-400
            text-start text-base font-medium text-indigo-700
            bg-indigo-50
            focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700
            transition duration-150 ease-in-out
        "
        : "
            flex items-center
            w-full ps-3 pe-4 py-2
            border-l-4 border-transparent
            text-start text-base font-medium text-gray-600
            hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300
            has-[:focus]:outline-none has-[:focus]:text-gray-800 has-[:focus]:bg-gray-50 has-[:focus]:border-gray-300
            transition duration-150 ease-in-out
        ";
@endphp

<div
    class="{{$classes}}"
>
    <x-dropdown align="{{$align}}" width="{{$width}}">
        <x-slot name="trigger">
            <button class="
                inline-flex items-center focus:outline-none
                {{$active ? 'focus:text-indigo-800' : 'focus:text-gray-800'}}
            ">
                <div x-data="{{ json_encode(['tag' => $tag]) }}" x-text="tag"></div>

                <div class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            {{ $slot }}
        </x-slot>
    </x-dropdown>