<table
    {!! $attributes->merge(['class' => "
        border-collapse table-auto w-full text-sm
    "]) !!}
>
    @if(isset($head))
        <thead>
            {{ $head }}
        </thead>
    @endif
    <tbody class="bg-white">
        {{ $body }}
    </tbody>
</table>
