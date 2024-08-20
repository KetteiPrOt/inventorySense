<x-layouts.primary
    header="Inventario"
>
    @if(isset($filters['warehouse']))
        <p class="mb-4">
            <strong>Bodega</strong> <br>
            {{$filters['warehouse']->name}}
        </p>
    @endif

    <p class="mb-4">
        <strong>Tipo de reporte</strong> <br>
        {{$filters['report_type']}}
    </p>

    <form action="{{request()->url()}}" class="mb-4 flex flex-col items-start sm:block">
        {{-- Search product input --}}
        <x-text-input
            placeholder="Producto..."
            name="search_product" minlength="2" maxlength="255"
            value="{{request()->query('search_product')}}"
        />
        <x-input-error :messages="$errors->get('search_product')" />
        <x-primary-button class="mt-1 sm:mt-0">
            Buscar
        </x-primary-button>
        {{-- Other query input --}}
        @foreach(request()->query() as $name => $value)
            @if($name !== 'search_product')
                <input hidden name="{{$name}}" value="{{$value}}">
                <x-input-error :messages="$errors->get($name)" />
            @endif
        @endforeach
    </form>

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
                        <x-icons.order
                            :data="[
                                'column' => 'tag',
                                'currentColumn' => $filters['column'],
                                'order' => $filters['order'],
                                'route' => 'inventory.index'
                            ]"
                        >Producto</x-icons.order>
                    </x-table.th>
                    <x-table.th>
                        Min. stock
                    </x-table.th>
                    <x-table.th>
                        <x-icons.order
                            :data="[
                                'column' => 'existences',
                                'currentColumn' => $filters['column'],
                                'order' => $filters['order'],
                                'route' => 'inventory.index'
                            ]"
                        >Cantidad</x-icons.order>
                    </x-table.th>
                    <x-table.th>
                        <x-icons.order
                            :data="[
                                'column' => 'unitary_price',
                                'currentColumn' => $filters['column'],
                                'order' => $filters['order'],
                                'route' => 'inventory.index'
                            ]"
                        >P. unitario</x-icons.order>
                    </x-table.th>
                    <x-table.th>
                        <x-icons.order
                            :data="[
                                'column' => 'total_price',
                                'currentColumn' => $filters['column'],
                                'order' => $filters['order'],
                                'route' => 'inventory.index'
                            ]"
                        >P. total</x-icons.order>
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
                        :danger="$product->existences == 0"
                        :alert="$product->existences < $product->min_stock"
                    >
                        <x-table.td
                            class="
                                text-center text-lg font-bold
                                sm:text-left sm:text-sm sm:font-normal
                            "
                        >
                            {{$product->tag}}
                        </x-table.td>
                        <x-table.td>
                            <span class="sm:hidden font-bold">
                                Stock mínimo:
                            </span>
                            {{$product->min_stock}}
                        </x-table.td>
                        <x-table.td>
                            <span class="hidden sm:inline">
                                {{$product->existences}}
                            </span>
                            {{-- Start Responsive table view --}}
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
                                                {{$product->existences}}
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
                            {{-- Finish Responsive table view --}}
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
                @if(
                    $products->isNotEmpty()
                    && $products->currentPage() == $products->lastPage()
                )
                    <x-table.tr
                        class="
                            flex flex-col sm:table-row
                            border-t-2 border-slate-300
                            sm:border-t-0
                        "
                    >
                        <x-table.td
                            class="
                                text-center text-lg font-bold
                                sm:text-left sm:text-sm sm:font-normal
                            "
                        >
                            <span class="sm:hidden">
                                Total:
                                ${{number_format(
                                    $products->sum('total_price'),
                                    2, '.', ','
                                )}}
                            </span>
                        </x-table.td>
                        <x-table.td class="hidden sm:table-cell"></x-table.td>
                        <x-table.td class="hidden sm:table-cell"></x-table.td>
                        <x-table.td class="hidden sm:table-cell">
                            Total:
                        </x-table.td>
                        <x-table.td class="hidden sm:table-cell">
                            ${{number_format(
                                $products->sum('total_price'),
                                2, '.', ','
                            )}}
                        </x-table.td>
                    </x-table.tr>
                @endif
            </x-slot:body>
        </x-table>
    </div>
    {{$products->links()}}

    @if($products->isEmpty())
        <p class="text-red-500">
            No se encontraron productos...
        </p>
    @endif
</x-layouts.primary>
