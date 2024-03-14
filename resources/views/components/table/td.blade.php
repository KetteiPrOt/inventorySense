<td
    {{ $attributes->merge(['class' => '
        border-b border-slate-100 text-slate-500
        p-2 sm:pr-4 sm:pl-8
    ']) }}
>
    {{$slot}}
</td>