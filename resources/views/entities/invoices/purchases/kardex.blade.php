<x-layouts.primary
    header="Kardex"
>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{$filters['product']->tag}}
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
    </header>

    <div class="lg:hidden mt-6">
        {{$movements->links()}}
    </div>

    <x-entities.invoices.purchases.kardex.table>
        {{-- table head is inside of the component --}}
        <x-slot:rows>
            @foreach($movements as $movement)
            <tr 
                class="
                    flex flex-col p-2 pb-3 border-t-2
                    hover:bg-slate-100 border-slate-300
                    lg:table-row lg:p-0 lg:border-0
                "
            >
                <td
                    class="
                        block text-lg font-bold
                        lg:table-cell
                        lg:p-1 lg:border lg:border-slate-100
                        lg:text-slate-500 lg:text-sm lg:font-normal
                    "
                >
                    {{date('d/m/Y', strtotime($movement->invoice->created_at))}}
                </td>
                <td
                    class="
                        block text-lg font-bold
                        lg:table-cell
                        lg:p-1 lg:border lg:border-slate-100
                        lg:text-slate-500 lg:text-sm lg:font-normal
                    "
                >
                    @if($movement->category === 'e')
                        {{$movement->invoice->provider?->name ?? 'Desconocido'}}
                    @else
                        {{$movement->invoice->client?->name ?? 'Consumidor final'}}
                    @endif
                </td>
                <td
                    class="block lg:table-cell lg:p-1 lg:border lg:border-slate-100 lg:text-slate-500"
                >
                    @if($movement->category === 'e')
                        <a href="{{route('purchases.show', $movement->invoice->id)}}">
                            <span class="hidden lg:inline underline text-blue-400">
                                {{$movement->invoice?->number ?? 'Sin número'}}
                            </span>
                            <span class="inline lg:hidden underline text-blue-400">
                                {{$movement->invoice?->number ?? 'Sin número de factura'}}
                            </span>
                        </a>
                    @else
                        <a href="{{route('sales.show', $movement->invoice->id)}}">
                            <span class="hidden lg:inline underline text-blue-400">
                                {{'ID: ' . $movement->invoice->id}}
                            </span>
                            <span class="inline lg:hidden underline text-blue-400">
                                {{'Factura ID: ' . $movement->invoice->id}}
                            </span>
                        </a>
                    @endif
                </td>
                <td
                    class="block lg:table-cell lg:p-1 lg:border lg:border-slate-100 lg:text-slate-500"
                >
                    <span class="lg:hidden">
                        <strong>Tipo:</strong>
                    </span>
                    {{$movement->type}}
                    {{-- Date and time --}}
                    <span class="mt-2 block lg:hidden">
                        {{date('d/m/Y H:i', strtotime($movement->invoice->created_at))}}
                    </span>
                    {{-- Movement details responsive table --}}
                    <div class="mt-2 block lg:hidden">
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
                                            $movement->unitary_purchase_price,
                                            2, '.', ','
                                        )}}
                                    </x-table.td>
                                    <x-table.td>
                                        ${{number_format(
                                            $movement->total_purchase_price,
                                            2, '.', ','
                                        )}}
                                    </x-table.td>
                                </x-table.tr>
                            </x-slot:body>
                        </x-table>
                    </div>
                    {{-- Balance detaild responsive table --}}
                    <div class="mt-3 block lg:hidden">
                        <strong class="block mb-1 text-center">Existencias</strong>
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
                                        {{$movement->balance->amount}}
                                    </x-table.td>
                                    <x-table.td>
                                        ${{number_format(
                                            $movement->balance->unitary_price,
                                            2, '.', ','
                                        )}}
                                    </x-table.td>
                                    <x-table.td>
                                        ${{number_format(
                                            $movement->balance->total_price,
                                            2, '.', ','
                                        )}}
                                    </x-table.td>
                                </x-table.tr>
                            </x-slot:body>
                        </x-table>
                    </div>
                </td>
                {{-- Incomes --}}
                <td class="hidden lg:table-cell lg:p-1 lg:border-b lg:border-slate-100 lg:text-slate-500 lg:text-center">
                    @if($movement->category === 'e')
                    {{$movement->amount}}
                    @endif
                </td>
                <td class="hidden lg:table-cell lg:p-1 lg:border-b lg:border-slate-100 lg:text-slate-500 lg:text-center">
                    @if($movement->category === 'e')
                    ${{number_format(
                        $movement->unitary_purchase_price,
                        2, '.', ','
                    )}}
                    @endif
                </td>
                <td class="hidden lg:table-cell lg:p-1 lg:border-b lg:border-r lg:border-slate-100 lg:text-slate-500 lg:text-center">
                    @if($movement->category === 'e')
                    ${{number_format(
                        $movement->total_purchase_price,
                        2, '.', ','
                    )}}
                    @endif
                </td>
                {{-- Expenses --}}
                <td class="hidden lg:table-cell lg:p-1 lg:border-b lg:border-slate-100 lg:text-slate-500 lg:text-center">
                    @if($movement->category === 'i')
                    {{$movement->amount}}
                    @endif
                </td>
                <td class="hidden lg:table-cell lg:p-1 lg:border-b lg:border-slate-100 lg:text-slate-500 lg:text-center">
                    @if($movement->category === 'i')
                    ${{number_format(
                        $movement->unitary_purchase_price,
                        2, '.', ','
                    )}}
                    @endif
                </td>
                <td class="hidden lg:table-cell lg:p-1 lg:border-b lg:border-r lg:border-slate-100 lg:text-slate-500 lg:text-center">
                    @if($movement->category === 'i')
                    ${{number_format(
                        $movement->total_purchase_price,
                        2, '.', ','
                    )}}
                    @endif
                </td>
                {{-- Balances --}}
                <td class="hidden lg:table-cell lg:p-1 lg:border-b lg:border-slate-100 lg:text-slate-500 lg:text-center">
                    {{$movement->balance->amount}}
                </td>
                <td class="hidden lg:table-cell lg:p-1 lg:border-b lg:border-slate-100 lg:text-slate-500 lg:text-center">
                    ${{number_format(
                        $movement->balance->unitary_price,
                        2, '.', ','
                    )}}
                </td>
                <td class="hidden lg:table-cell lg:p-1 lg:border-b lg:border-r lg:border-slate-100 lg:text-slate-500 lg:text-center">
                    ${{number_format(
                        $movement->balance->total_price,
                        2, '.', ','
                    )}}
                </td>
            </tr>
            @endforeach
        </x-slot:rows>
    </x-entities.invoices.purchases.kardex.table>

    <div class="mt-2">
        {{$movements->links()}}
        @if($movements->isEmpty())
            <p>No se encontraron movimientos...</p>
        @endif
    </div>
</x-layouts.primary>
