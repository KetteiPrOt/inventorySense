<x-layouts.primary
    header="Inventario"
>
    @if(isset($filters['warehouse']))
        <p class="mb-6">
            <strong>Bodega</strong> <br>
            {{$filters['warehouse']->name}}
        </p>
    @endif

    <div class="mb-2 sm:hidden">
        {{$products->links()}}
    </div>

    <div class="mb-2 max-w-7xl overflow-x-auto">
        <x-table>
            <x-slot:head>
                <x-table.tr
                    class="hidden sm:table-row"
                    :hover="false"
                >
                    <x-table.th>
                        Producto
                    </x-table.th>
                    @if(!isset($filters['warehouse']))
                        <x-table.th>
                            Min. stock
                        </x-table.th>
                    @endif
                    <x-table.th>
                        Cantidad
                    </x-table.th>
                    <x-table.th>
                        P. unitario
                    </x-table.th>
                    <x-table.th>
                        P. total
                    </x-table.th>
                </x-table.tr>
            </x-slot:head>
            <x-slot:body>
                @foreach($products as $product)
                    <x-table.tr
                        class="
                            flex flex-col sm:table-row
                            border-t-2 border-slate-300
                            sm:border-t-0
                        "
                        :danger="
                            !isset($filters['warehouse'])
                            && ($product->amount < $product->min_stock)
                        "
                        :alert="
                            !isset($filters['warehouse'])
                            && ($product->amount == $product->min_stock)
                        "
                    >
                        <x-table.td
                            class="
                                text-center text-lg font-bold
                                sm:text-left sm:text-sm sm:font-normal
                            "
                        >
                            {{$product->tag}}
                        </x-table.td>
                        @if(!isset($filters['warehouse']))
                            <x-table.td>
                                <span class="sm:hidden font-bold">
                                    Stock mínimo:
                                </span>
                                {{$product->min_stock}}
                            </x-table.td>
                        @endif
                        <x-table.td>
                            <span class="hidden sm:inline">
                                {{$product->amount}}
                            </span>
                            {{-- Responsive table view --}}
                            <div class="block sm:hidden">
                                <x-table>
                                    <x-slot:head>
                                        <x-table.tr :hover="false">
                                            <x-table.th class="text-center">
                                                CANT
                                            </x-table.th>
                                            <x-table.th class="text-center">
                                                P.UNIT
                                            </x-table.th>
                                            <x-table.th class="text-center">
                                                P.TOTAL
                                            </x-table.th>
                                        </x-table.tr>
                                    </x-slot:head>
                                    <x-slot:body>
                                        <x-table.tr>
                                            <x-table.td class="text-center">
                                                {{$product->amount}}
                                            </x-table.td>
                                            <x-table.td class="text-center">
                                                ${{number_format(
                                                    $product->unitary_price,
                                                    2, '.', ','
                                                )}}
                                            </x-table.td>
                                            <x-table.td class="text-center">
                                                ${{number_format(
                                                    $product->total_price,
                                                    2, '.', ','
                                                )}}
                                            </x-table.td>
                                        </x-table.tr>
                                    </x-slot:body>
                                </x-table>
                            </div>
                        </x-table.td>
                        <x-table.td class="hidden sm:table-cell">
                            ${{number_format(
                                $product->unitary_price,
                                2, '.', ','
                            )}}
                        </x-table.td>
                        <x-table.td class="hidden sm:table-cell">
                            ${{number_format(
                                $product->total_price,
                                2, '.', ','
                            )}}
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            </x-slot:body>
        </x-table>
    </div>
    {{$products->links()}}

    @if($products->isEmpty())
        <p class="text-red-500">
            @if(isset($filters['warehouse']))
                No se encontraron existencias de productos en esta bodega...
            @else
                No se encontraron productos registrados...
            @endif
        </p>
    @endif
</x-layouts.primary>
