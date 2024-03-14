@props(['danger' => false, 'hover' => true])

<tr class="
    @if($hover)
        @if($danger)
            bg-red-100 hover:bg-red-200
        @else
            hover:bg-slate-100 
        @endif
    @endif
">
    {{$slot}}
</tr>