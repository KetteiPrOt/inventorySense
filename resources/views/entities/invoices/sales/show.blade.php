<x-layouts.primary
    header="Ver venta"
>
    <div class="sm:flex mb-6">
        <div class="mb-4 sm:mb-0 sm:mr-12">
            <p>
                <strong>Factura ID</strong> <br>
                {{$invoice->id}}
            </p>
            <p>
                <strong>Bodega</strong> <br>
                {{$invoice->warehouse->name}}
            </p>
            <p>
                <strong>Usuario</strong> <br>
                {{$invoice->user?->name ?? 'Eliminado'}}
            </p>
            <p>
                <strong>Cliente</strong> <br>
                @if(!is_null($invoice->client))
                    <a href="{{route('clients.show', $invoice->client->id)}}" class="text-blue-400 underline">
                        {{$invoice->client->name}}
                    </a>
                @else
                    Consumidor Final
                @endif
            </p>
            <p>
                <strong>Total</strong> <br>
                ${{number_format(
                    $total_prices_summation,
                    2, '.', ','
                )}}
            </p>
        </div>
        <div>
            <p>
                <strong>Creado el</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($invoice->created_at));
                }}
            </p>
            <p>
                <strong>Ultima actualización</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($invoice->updated_at));
                }}
            </p>
        </div>
    </div>

    @if($invoice->comment)
    <article class="mb-6">
        <h3
            class="font-bold text-lg"
        >Comentario</h3>
        <p>
            {{$invoice->comment}}
        </p>
    </article>
    @endif

    <div class="mb-6 max-w-7xl overflow-x-auto">
    <x-table>
        <x-slot:head>
            <x-table.tr
                :hover="false"
                class="hidden sm:table-row"
            >
                <x-table.th>
                    Producto
                </x-table.th>
                <x-table.th>
                    Cantidad
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
                            block text-center font-bold
                            sm:table-cell sm:font-normal sm:text-left
                        "
                    >
                        {{$movement->product_tag}}
                    </x-table.td>
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

    <div class="space-y-4">
        <p>
            <strong>Venta Pagada:</strong>
            @if($invoice->paid)
                <span class="text-green-500">
                    Si
                </span>
            @else
                <span class="text-red-500">
                    No
                </span>
            @endif
        </p>
        @if($invoice->paid)
            <p>
                <strong>Fecha de pago:</strong>
                {{date('d/m/Y', strtotime($invoice->paid_date ?? $invoice->created_at))}}
            </p>
        @else
            <p>
                <strong>Vencimiento de pago:</strong>
                {{date('d/m/Y', strtotime($invoice->due_payment_date))}}
            </p>

            @can('edit-all-sales')
                <form
                    class="flex flex-col items-center sm:items-start"
                    action="{{route('sales.update', $invoice->id)}}" method="post"
                >
                    @csrf
                    @method('put')

                    <div class="mt-6 mb-2">
                        <x-primary-button>
                            Confirmar pago
                        </x-primary-button>
                    </div>

                    <x-input-label for="paidDateInput">
                        Fecha de pago
                    </x-input-label>
                    <x-date-input
                        name="paid_date" id="paidDateInput"
                        value="{{date('Y-m-d')}}"
                        min="{{date('Y-m-d', strtotime($invoice->created_at))}}"
                        max="{{date('Y-m-d')}}"
                    />
                    <x-input-error :messages="$errors->get('paid_date')" />
                </form>
            @endcan
        @endif
    </div>
</x-layouts.primary>
