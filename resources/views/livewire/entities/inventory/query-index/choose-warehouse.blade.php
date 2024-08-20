<div>
    <x-input-label for="warehouseSearch" :required="$required">
        Bodega
    </x-input-label>
    <x-text-input
        id="warehouseSearch"
        class="mt-1 block w-full max-w-sm"
        placeholder="Buscar..."
        maxlength="255"
        wire:model.live="search"
    />
    <x-table class="max-w-sm">
        <x-slot:body>
            @if(!is_null($warehouses))
            @foreach($warehouses as $warehouse)
                <x-table.tr>
                    <x-table.td>
                        <input
                            type="radio"
                            value="{{$warehouse->id}}"
                            @required($required)
                            id="warehouseInput{{$warehouse->id}}"
                            class="warehouse-input rounded-full mr-2"
                            name="warehouse"
                        />
                        <label for="warehouseInput{{$warehouse->id}}">
                            {{$warehouse->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endforeach
            @endif
            @if(!is_null($warehouseSelectedByDefault))
                <x-table.tr>
                    <x-table.td>
                        <input
                            checked
                            type="radio"
                            value="{{$warehouseSelectedByDefault->id}}"
                            @required($required)
                            id="warehouseInput{{$warehouseSelectedByDefault->id}}"
                            class="warehouse-input rounded-full mr-2"
                            name="warehouse"
                        />
                        <label for="warehouseInput{{$warehouseSelectedByDefault->id}}">
                            {{$warehouseSelectedByDefault->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endif
        </x-slot:body>
    </x-table>
    <div class="max-w-sm mt-2">
        {{$warehouses?->links(data: ['scrollTo' => false])}}
        @if($warehouses?->isEmpty() && is_null($warehouseSelectedByDefault))
            <span class="text-red-500">
                No se encontraron bodegas...
            </span>
        @endif
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('warehouse')" />
</div>
