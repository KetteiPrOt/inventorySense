@props(['tag', 'align' => 'right', 'width' => 48, 'active' => false])

@php
    $frameClasses = $active
        ? "
            flex items-center ms-6 border-b-2 border-indigo-400
            has-[:focus]:outline-none has-[:focus]:border-indigo-700 transition duration-150 ease-in-out
        "
        : "
            flex items-center ms-6 border-b-2 border-transparent
            hover:border-gray-300
            has-[:focus]:border-gray-300 transition duration-150 ease-in-out
        ";
    $classes = $active
        ? "
            inline-flex items-center px-1 pt-1
            text-sm font-medium leading-5 text-gray-900 
            focus:outline-none transition duration-150 ease-in-out
        "
        : "
            inline-flex items-center px-1 pt-1
            text-sm font-medium leading-5 text-gray-500 hover:text-gray-700
            focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out
        ";
@endphp

<div class="{{$frameClasses}}">
    <x-dropdown align="{{$align}}" width="{{$width}}">
        <x-slot name="trigger">
            <button class="{{$classes}}">
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
</div>
