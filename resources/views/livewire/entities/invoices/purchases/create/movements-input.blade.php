<div>

@if($selectedProductsCollection->isNotEmpty())
    <x-table>
        <x-slot:head>
            <x-table.tr :hover="false">
                <x-table.th class="hidden lg:table-cell">
                    Nombre
                </x-table.th>
                <x-table.th class="hidden lg:table-cell lg:text-center">
                    Tipo
                </x-table.th>
                <x-table.th class="hidden lg:table-cell lg:text-center">
                    Cantidad
                </x-table.th>
                <x-table.th class="hidden lg:table-cell lg:text-center">
                    Precio unitario
                </x-table.th>
                <x-table.th class="hidden lg:table-cell lg:text-center">
                    Precio total
                </x-table.th>
                <x-table.th class="hidden lg:table-cell"></x-table.th>
            </x-table.tr>
        </x-slot:head>
        <x-slot:body>
            @foreach($selectedProductsCollection as $product)
                <x-table.tr
                    wire:key="{{$product->id}}"
                    class="
                        grid grid-flow-col
                        gird-cols-1 grid-rows-6
                        lg:table-row
                        border-b-2 border-t-2 lg:border-b lg:border-t-0
                    "
                    x-data="movementInput"
                    x-on:changed-movement-input-value="synchronizeValues"
                >
                    <x-table.td
                        class="
                            col-span-1
                            flex items-center justify-center text-center
                            lg:table-cell
                            text-base
                            font-bold
                            lg:font-normal
                            lg:text-left
                        "
                    >
                        <input name="products[]" value="{{$product->id}}"  hidden>
                        {{$product->tag}}
                    </x-table.td>
                    <x-table.td
                        class="col-span-1 lg:text-center flex items-center lg:table-cell"
                    >
                        <div class="flex items-center lg:justify-center">
                            <label class="mr-1 lg:hidden" for="movementTypeInput{{$product->id}}">
                                Tipo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </label>
                            <select
                                name="movement_types[]"
                                class="
                                    p-1 pr-8
                                    border-gray-300
                                    focus:border-indigo-500 focus:ring-indigo-500
                                    rounded-md shadow-sm
                                "
                                id="movementTypeInput{{$product->id}}"
                            >
                                @if($product->started_inventory)
                                    @foreach($movementTypes as $type)
                                        <option
                                            value="{{$type->id}}"
                                        >{{$type->name}}</option>
                                    @endforeach
                                @else
                                        <option
                                            value="{{$initialInventory->id}}"
                                        >{{$initialInventory->name}}</option>
                                @endif
                            </select>
                        </div>
                    </x-table.td>
                    <x-table.td
                        class="col-span-1 lg:text-center  flex items-center lg:table-cell"
                    >
                        <div class="flex items-center lg:justify-center">
                            <label for="amountInput{{$product->id}}" class="mr-1 lg:hidden">
                                Cantidad
                            </label>
                            <x-number-input
                                name="amounts[]"
                                class="w-48 lg:w-20"
                                id="amountInput{{$product->id}}"
                                min="1" max="65000" step="1" required
                                x-model="amount"
                                x-on:keyup="$dispatch('changed-movement-input-value')"
                            />
                        </div>
                    </x-table.td>
                    <x-table.td
                        class="col-span-1 lg:text-center  flex items-center lg:table-cell"
                    >
                        <div class="flex items-center lg:justify-center">
                            <label for="unitaryPurchasePriceInput{{$product->id}}" class="mr-1 lg:hidden">
                                Precio&nbsp;&nbsp;&nbsp;
                            </label>
                            $<x-number-input
                                name="unitary_purchase_prices[]"
                                class="w-48 lg:w-20"
                                id="unitaryPurchasePriceInput{{$product->id}}"
                                min="0.01" max="999999.99" step="0.01" required
                                x-model="unitaryPrice"
                                x-on:keyup="$dispatch('changed-movement-input-value')"
                            />
                        </div>
                    </x-table.td>
                    <x-table.td
                        class="col-span-1 lg:text-center  flex items-center lg:table-cell"
                    >
                        <div class="flex items-center lg:justify-center">
                            <input x-model="totalPrice" type="number" hidden class="total-purchase-price-input">
                            <label class="mr-1 lg:hidden">
                                Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </label>
                            $<x-text-input
                                class="w-48 lg:w-20" readonly
                                id="totalPurchasePriceInput{{$product->id}}"
                                x-model="displayTotalPrice"
                            />
                        </div>
                    </x-table.td>
                    <x-table.td
                        class="
                            col-span-1 text-center
                            flex items-center justify-center
                            lg:table-cell
                        "
                    >
                        <x-secondary-button
                            x-on:click="
                                $wire.removeProduct({{$product->id}});
                                $dispatch('changed-movement-input-value');
                            "
                            class="bg-red-500 hover:bg-red-400"
                        >
                            <x-icons.plus
                                color="#fff"
                                class="w-10 lg:w-3 h-3 rotate-45"
                            />
                        </x-secondary-button>
                    </x-table.td>
                </x-table.tr>
            @endforeach
            <x-table.tr
                class="
                    grid
                    lg:table-row
                    border-b-2 border-t-2 lg:border-b lg:border-t-0
                    bg-slate-100 lg:bg-white
                "
                x-data="{
                    displayTotal: '$0.00',
                    synchronizeTotal() {
                        const inputs = Array.from(
                            document.querySelectorAll('.total-purchase-price-input')
                        );
                        let summation = 0;
                        for(const input of inputs){
                            summation += parseFloat(input.value);
                        }
                        const formater = $store.priceFormater.create();
                        this.displayTotal = `$${formater.format(summation)}`;
                    }
                }"
                x-on:changed-movement-input-value.window.debounce="synchronizeTotal"
            >
                <x-table.td class="hidden lg:table-cell"></x-table.td>
                <x-table.td class="hidden lg:table-cell"></x-table.td>
                <x-table.td class="hidden lg:table-cell"></x-table.td>
                <x-table.td class="hidden lg:table-cell text-center text-base font-bold">
                    Total:
                </x-table.td>
                <x-table.td class="lg:table-cell lg:text-center">
                    <span class="block mb-1 lg:hidden text-base font-bold">
                        Total a pagar&nbsp;
                    </span>
                    <span x-text="displayTotal"></span>
                </x-table.td>
                <x-table.td class="hidden lg:table-cell"></x-table.td>
            </x-table.tr>
        </x-slot:body>
    </x-table>
@endif

<div class="mt-6">
    <x-input-label for="searchProductInput">
        Productos
    </x-input-label>
    <x-text-input
        wire:model.live="search"
        id="searchProductInput"
        class="mt-1 block w-full max-w-sm"
        minlength="2" maxlength="255"
        placeholder="Buscar..."
    />
</div>

@if($searchedProducts->isNotEmpty())
    <x-table class="mt-2 mb-1 max-w-sm">
        <x-slot:head>
            <x-table.tr :hoder="false">
                <x-table.th>
                    Nombre
                </x-table.th>
                <x-table.th></x-table.th>
            </x-table.tr>
        </x-slot:head>
        <x-slot:body>
            @foreach($searchedProducts as $product)
                <x-table.tr wire:key="{{$product->id}}">
                    <x-table.td>
                        {{$product->tag}}
                    </x-table.td>
                    <x-table.td>
                        <x-secondary-button
                            wire:click="addProduct({{$product->id}})"
                        >
                            <x-icons.take-hand class="w-5 h-5" />
                        </x-secondary-button>
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
    <div class="max-w-sm">
        {{$searchedProducts->links(data: ['scrollTo' => false])}}
    </div>
@endif

@script
<script>
    Alpine.store('priceFormater', {
        create() {
            return new Intl.NumberFormat('en-US', {
                style: 'decimal',
                minimumIntegerDigits: 1,
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    });

    Alpine.data('movementInput', () => {
        return {
            amount: 1,
            unitaryPrice: 0,
            totalPrice: 0.00,
            displayTotalPrice: '0.00',
            synchronizeValues() {
                this.totalPrice = (this.amount * this.unitaryPrice).toFixed(2);
                const formater = $store.priceFormater.create();
                this.displayTotalPrice = formater.format(this.totalPrice);
            }
        }
    });
</script>
@endscript

</div> 
