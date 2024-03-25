<div>
    <x-input-label for="productPresentationSearch" :required="true">
        Presentación
    </x-input-label>
    <x-text-input
        id="productPresentationSearch"
        class="mt-1 block w-full max-w-sm"
        placeholder="Buscar..."
        maxlength="49"
        wire:model.live="search"
    />
    <x-table class="max-w-sm">
        <x-slot:body>
            @if(!is_null($presentations))
            @foreach($presentations as $presentation)
                <x-table.tr>
                    <x-table.td>
                        <input
                            type="radio"
                            value="{{$presentation->id}}"
                            required
                            id="presentationInput{{$presentation->id}}"
                            class="rounded-full mr-2"
                            name="product_presentation"
                        />
                        <label for="presentationInput{{$presentation->id}}">
                            {{$presentation->content}}ml
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endforeach
            @endif
            @if(!is_null($presentationSelectedByDefault))
                <x-table.tr>
                    <x-table.td>
                        <input
                            checked
                            type="radio"
                            value="{{$presentationSelectedByDefault->id}}"
                            required
                            id="presentationInput{{$presentationSelectedByDefault->id}}"
                            class="rounded-full mr-2"
                            name="product_presentation"
                        />
                        <label for="presentationInput{{$presentationSelectedByDefault->id}}">
                            {{$presentationSelectedByDefault->content}}ml
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endif
        </x-slot:body>
    </x-table>
    <div class="max-w-sm mt-2">
        {{$presentations?->links(data: ['scrollTo' => false])}}
        @if($presentations?->isEmpty() && is_null($presentationSelectedByDefault))
            <span class="text-red-500">
                No se encontraron presentaciones...
            </span>
        @endif
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('product_presentation')" />
</div>
