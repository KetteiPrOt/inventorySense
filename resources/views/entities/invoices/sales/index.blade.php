<x-layouts.primary
    header="Reporte de ventas"
>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{$filters['report_type']}}
        </h2>

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
        @else
            @if(!auth()->user()->can('see-all-sales'))
            <div>
                <p>
                    <strong>Vendedor</strong> <br>
                    {{auth()->user()->name}}
                </p>
            </div>
            @endif
        @endif

        @if(isset($filters['client']))
        <div>
            <p>
                <strong>Bodega</strong> <br>
                {{$filters['client']->name}}
            </p>
        </div>
        @endif
    </header>

    <div class="md:hidden mt-6">
        {{$invoices->links()}}
    </div>

    <div class="max-w-7xl overflow-x-auto">
        <x-table class="mt-6">
            <x-slot:head>
                <x-table.tr
                    :hover="false"
                    class="hidden md:table-row"
                >
                    <x-table.th>
                        Fecha
                    </x-table.th>
                    <x-table.th>
                        Cliente
                    </x-table.th>
                    <x-table.th>
                        Factura
                    </x-table.th>
                    <x-table.th>
                        P.Total
                    </x-table.th>
                    <x-table.th>
                        Fecha de pago
                    </x-table.th>
                    <x-table.th>
                        Pagada
                    </x-table.th>
                    <x-table.th>
                        Vencimiento
                    </x-table.th>
                </x-table.tr>
            </x-slot:head>
            <x-slot:body>
                @foreach($invoices as $invoice)
                    <x-table.tr
                        class="
                            flex flex-col md:table-row
                            border-t-2 border-slate-300
                            md:border-t-0
                        "
                        :alert="
                            !$invoice->paid
                            && (date('Y-m-d') <= $invoice->due_payment_date)
                        "
                        :danger="
                            !$invoice->paid
                            && (date('Y-m-d') > $invoice->due_payment_date)
                        "
                    >
                        <x-table.td class="order-3">
                            <span class="md:hidden font-bold">
                                Fecha:
                            </span>
                            {{date('d/m/Y', strtotime($invoice->date))}}
                        </x-table.td>
                        <x-table.td
                            class="
                                order-1
                                block text-lg font-bold
                                md:table-cell md:text-sm md:font-normal
                            "
                        >
                            <span class="hidden md:inline">
                                {{$invoice->client?->name ?? 'Consumidor final'}}
                            </span>
                            <span class="md:hidden">
                                {{$invoice->client?->name ?? 'Consumidor final'}}
                            </span>
                        </x-table.td>
                        <x-table.td
                            class="order-2 block md:table-cell"
                        >
                            <a href="{{route('sales.show', $invoice->id)}}">
                                <span class="hidden md:inline underline text-blue-400">
                                    ID: {{$invoice->id}}
                                </span>
                                <span class="md:hidden underline text-blue-400">
                                    Factura ID: {{$invoice->id}}
                                </span>
                            </a>
                        </x-table.td>
                        <x-table.td
                            class="order-3 block md:table-cell"
                        >
                            <span class="hidden md:inline">
                                ${{number_format(
                                    $invoice->total_price,
                                    2, '.', ','
                                )}}
                            </span>
                            {{-- Responsive view of invoice data --}}
                            <div class="flex md:hidden">
                                <div class="mr-4">
                                    <strong>Precio total</strong> <br>
                                    ${{number_format(
                                        $invoice->total_price,
                                        2, '.', ','
                                    )}}
                                </div>
                                <div>
                                    <strong>Pagada</strong> <br>
                                    {{$invoice->paid ? 'Si' : 'No'}}
                                </div>
                            </div>
                            <div class="flex mt-2 md:hidden">
                                <div class="mr-4">
                                    <strong>Fecha de pago</strong> <br>
                                    @if(!$invoice->paid)
                                        Ninguna
                                    @else
                                        {{date('d/m/Y', strtotime($invoice->paid_date ?? $invoice->created_at))}}
                                    @endif
                                </div>
                                <div>
                                    <strong>Fecha de vencimiento</strong> <br>
                                    @if($invoice->paid)
                                        Ninguna
                                    @else
                                        {{date('d/m/Y', strtotime($invoice->due_payment_date))}}
                                    @endif
                                </div>
                            <div class="flex md:hidden">
                        </x-table.td>
                        <x-table.td
                            class="hidden md:table-cell"
                        >
                            @if(!$invoice->paid)
                                Ninguna
                            @else
                                {{date('d/m/Y', strtotime($invoice->paid_date ?? $invoice->created_at))}}
                            @endif
                        </x-table.td>
                        <x-table.td
                            class="hidden md:table-cell"
                        >
                            {{$invoice->paid ? 'Si' : 'No'}}
                        </x-table.td>
                        <x-table.td
                            class="hidden md:table-cell"
                        >
                            @if($invoice->paid)
                                Ninguno
                            @else
                                {{date('d/m/Y', strtotime($invoice->due_payment_date))}}
                            @endif
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            </x-slot:body>
        </x-table>
        </div>
    
        <div class="mt-2">
            {{$invoices->links()}}
        </div>
        @if($invoices->isEmpty())
            <p class="text-red-500">
                No se encontraron ventas...
            </p>
        @endif
</x-layouts.primary>
