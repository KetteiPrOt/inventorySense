<x-layouts.primary
    header="Cierre de caja"
>
    <section class="space-y-2">
        <div class="flex mt-4">
            <p class="mr-4">
                <strong>Desde</strong> <br>
                {{date('d/m/Y', strtotime($filters['date_from']))}}
            </p>

            <p>
                <strong>Hasta</strong> <br>
                {{date('d/m/Y', strtotime($filters['date_to']))}}
            </p>
        </div>

        @if(isset($filters['warehouse']))
        <div>
            <p>
                <strong>Bodega</strong> <br>
                {{$filters['warehouse']->name}}
            </p>
        </div>
        @endif

        @if(isset($filters['user']))
        <div>
            <p>
                <strong>Vendedor</strong> <br>
                {{$filters['user']->name}}
            </p>
        </div>
        @endif

        @if(isset($filters['product']))
        <div>
            <p>
                <strong>Producto</strong> <br>
                {{$filters['product']->tag}}
            </p>
        </div>
        @endif

        <div>
            <p>
                <strong>Total</strong> <br>
                ${{number_format(
                    $total_prices_summation,
                    2, '.', ','
                )}}
            </p>
        </div>
    </section>

    <div class="mt-6 mb-2 sm:hidden">
        {{$movements->links()}}
    </div>
    <div class="max-w-7xl overflow-x-auto">
        <x-table>
            <x-slot:head>
                <x-table.tr
                    :hover="false"
                    class="hidden sm:table-row"
                >
                    <x-table.th>
                        Factura
                    </x-table.th>
                    @if(!isset($filters['product']))
                        <x-table.th>
                            Producto
                        </x-table.th>
                    @endif
                    <x-table.th>
                        CANT
                    </x-table.th>
                    <x-table.th>
                        P.UNIT
                    </x-table.th>
                    <x-table.th>
                        P.TOTAL
                    </x-table.th>
                </x-table.tr>
            </x-slot:head>
            <x-slot:body>
                @foreach($movements as $movement)
                    <x-table.tr
                        class="
                            flex flex-col sm:table-row
                            border-t-2 border-slate-300
                            sm:border-t-0
                        "
                    >
                        <x-table.td
                            class="
                                block text-lg font-bold
                                sm:table-cell sm:text-sm sm:font-normal
                            "
                        >
                            <span class="hidden sm:inline">
                                ID: {{$movement->invoice->id}}
                            </span>
                            <span class="inline sm:hidden">
                                Factura ID: {{$movement->invoice->id}}
                            </span>
                        </x-table.td>
                        @if(!isset($filters['product']))
                            <x-table.td
                                class="block sm:table-cell"
                            >
                                {{$movement->product_tag}}
                            </x-table.td>
                        @endif
                        <x-table.td
                            class="block sm:table-cell"
                        >
                            <span class="hidden sm:inline">
                                {{$movement->amount}}
                            </span>
                            {{--Income details responsive table --}}
                            <div class="mt-2 block sm:hidden">
                                <x-table>
                                    <x-slot:head>
                                        <x-table.tr :hover="false">
                                            <x-table.th>
                                                Cantidad
                                            </x-table.th>
                                            <x-table.th>
                                                P. Unitario
                                            </x-table.th>
                                            <x-table.th>
                                                P. Total
                                            </x-table.th>
                                        </x-table.tr>
                                    </x-slot:head>
                                    <x-slot:body>
                                        <x-table.tr :hover="false">
                                            <x-table.td>
                                                {{$movement->amount}}
                                            </x-table.td>
                                            <x-table.td>
                                                ${{number_format(
                                                    $movement->unitary_sale_price,
                                                    2, '.', ','
                                                )}}
                                            </x-table.td>
                                            <x-table.td>
                                                ${{number_format(
                                                    $movement->total_sale_price,
                                                    2, '.', ','
                                                )}}
                                            </x-table.td>
                                        </x-table.tr>
                                    </x-slot:body>
                                </x-table>
                            </div>
                        </x-table.td>
                        <x-table.td
                            class="hidden sm:table-cell"
                        >
                            ${{number_format(
                                $movement->unitary_sale_price,
                                2, '.', ','
                            )}}
                        </x-table.td>
                        <x-table.td
                            class="hidden sm:table-cell"
                        >
                            ${{number_format(
                                $movement->total_sale_price,
                                2, '.', ','
                            )}}
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            </x-slot:body>
        </x-table>
    </div>
    <div class="mt-2">
        {{$movements->links()}}
    </div>
    @if($movements->isEmpty())
        <p class="text-red-500">
            No se encontraron ventas...
        </p>
    @endif
</x-layouts.primary>
