<x-layouts.primary
    header="Ver producto"
>
    <div class="sm:flex mb-6">
        <div class="mb-4 sm:mb-0 sm:mr-12">
            <p>
                <strong>Producto</strong> <br>
                {{$product->tag}}
            </p>
            <p>
                <strong>Inventario</strong> <br>
                @if($product->started_inventory)
                    <span class="text-green-500">
                        Si iniciado.
                    </span>
                @else
                    <span class="text-red-500">
                        No iniciado.
                    </span>
                @endif
            </p>
            <p>
                <strong>Stock mínimo</strong> <br>
                {{$product->min_stock}}
                {{$product->min_stock == 1
                    ? 'unidad.' : 'unidades.'}}
            </p>
        </div>
        <div>
            <p>
                <strong>Creado el</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($product->created_at));
                }}
            </p>
            <p>
                <strong>Ultima actualización</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($product->updated_at));
                }}
            </p>
        </div>
    </div>

    <p>
        <strong>Precios de venta</strong>
    </p>

    <x-table class="max-w-sm mb-6">
        <x-slot:head>
            <x-table.tr :hover="false">
                <x-table.th>
                    Desde
                </x-table.th>
                <x-table.th>
                    Precio de venta
                </x-table.th>
            </x-table.tr>
        </x-slot:head>
        <x-slot:body>
            @foreach($product->salePrices as $salePrice)
                <x-table.tr>
                    <x-table.td>
                        {{
                            $salePrice->units_number . ' '
                            . ($salePrice->units_number == 1 ? 'unidad' : 'unidades')
                        }}
                    </x-table.td>
                    <x-table.td>
                        ${{$salePrice->price}}
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>

    <div>
        <x-secondary-link-button
            :href="route('products.edit', $product->id)"
        >
            Editar
        </x-secondary-link-button>
        <x-danger-button>
            Eliminar
        </x-danger-button>
    </div>

</x-layouts.primary>