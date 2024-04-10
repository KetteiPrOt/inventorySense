<div>
    <x-input-label for="productSearch" :required="$required">
        Producto
    </x-input-label>
    <x-text-input
        id="productSearch"
        class="mt-1 block w-full max-w-sm"
        placeholder="Buscar..."
        maxlength="255"
        wire:model.live="search"
    />
    <x-table class="max-w-sm mt-1">
        <x-slot:body>
            @if(!is_null($products))
            @foreach($products as $product)
                @if($onlyWithStartedInventory && !$product->started_inventory)
                    <x-table.tr :danger="true">
                        <x-table.td>
                            <input
                                type="radio"
                                value="{{$product->id}}"
                                @required($required)
                                id="productInput{{$product->id}}"
                                class="rounded-full mr-2 opacity-30"
                                name="product"
                                disabled
                            />
                            <label for="productInput{{$product->id}}">
                                {{$product->tag}}
                            </label>
                        </x-table.td>
                    </x-table.tr>
                @else
                    <x-table.tr>
                        <x-table.td>
                            <input
                                type="radio"
                                value="{{$product->id}}"
                                @required($required)
                                id="productInput{{$product->id}}"
                                class="rounded-full mr-2"
                                name="product"
                            />
                            <label for="productInput{{$product->id}}">
                                {{$product->tag}}
                            </label>
                        </x-table.td>
                    </x-table.tr>
                @endif
            @endforeach
            @endif
            @if(!is_null($productSelectedByDefault))
                <x-table.tr>
                    <x-table.td>
                        <input
                            checked
                            type="radio"
                            value="{{$productSelectedByDefault->id}}"
                            @required($required)
                            id="productInput{{$productSelectedByDefault->id}}"
                            class="rounded-full mr-2"
                            name="product"
                        />
                        <label for="productInput{{$productSelectedByDefault->id}}">
                            {{$productSelectedByDefault->tag}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endif
        </x-slot:body>
    </x-table>
    <div class="max-w-sm mt-2">
        {{$products?->links(data: ['scrollTo' => false])}}
        @if($products?->isEmpty() && is_null($productSelectedByDefault))
            <span class="text-red-500">
                No se encontraron productos...
            </span>
        @endif
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('product')" />
</div>
