<div>
    <x-input-label for="productTypeSearch" :required="true">
        Tipo
    </x-input-label>
    <x-text-input
        id="productTypeSearch"
        class="mt-1 block w-full max-w-sm"
        placeholder="Buscar..."
        maxlength="49"
        wire:model.live="search"
    />
    <x-table class="max-w-sm">
        <x-slot:body>
            @if(!is_null($types))
            @foreach($types as $type)
                <x-table.tr>
                    <x-table.td>
                        <input
                            type="radio"
                            value="{{$type->id}}"
                            required
                            id="typeInput{{$type->id}}"
                            class="rounded-full mr-2"
                            name="product_type"
                        />
                        <label for="typeInput{{$type->id}}">
                            {{$type->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endforeach
            @endif
        </x-slot:body>
    </x-table>
    <div class="max-w-sm mt-2">
        {{$types?->links(data: ['scrollTo' => false])}}
        @if($types?->isEmpty())
            <span class="text-red-500">
                No se encontraron tipos...
            </span>
        @endif
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('product_type')" />
</div>
