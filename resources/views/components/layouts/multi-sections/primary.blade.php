@props(['header', 'title' => null, 'totalSections' => 1])

<x-app-layout :$title>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{$header}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{$slot}}

            @for($i = 0; $i < $totalSections; $i++)
                @php
                    $section = 'section_' . ($i + 1);
                @endphp

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        {{$$section}}
                    </div>
                </div>
            @endfor
        </div>
    </div>
</x-app-layout>
