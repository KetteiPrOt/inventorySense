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
                    x-data="movementInput(
                        {{$product->salePrices[0]->price}},
                        {{$product->id}}
                    )"
                    x-on:changed-movement-input-value="synchronizeValues(); updateSalePrices()"
                >
                    <x-table.td
                        class="
                            col-span-1
                            flex items-center justify-center text-center
                            lg:table-cell
                            lg:text-left
                        "
                    >
                        <input name="products[]" value="{{$product->id}}"  hidden>
                        <button
                            class="
                                inline
                                text-base
                                font-bold
                                lg:text-sm
                                lg:font-normal
                                lg:text-left
                            "
                            x-data
                            x-on:click.prevent="$dispatch('open-modal', 'info-product{{$product->id}}')"
                        >{{$product->tag}}</button>
                        <x-modal name="info-product{{$product->id}}">
                            <div class="text-left p-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Ofertas especiales
                                </h3>
                                @if($product->salePrices->count() === 1)
                                    <p class="mt-1 text-sm text-blue-600">
                                        Este producto no tiene precios de oferta actualmente....
                                    </p>
                                @else
                                    <x-table class="max-w-sm mt-1">
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
                                            @foreach($product->salePrices->sortBy('units_number') as $salePrice)
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
                                @endif
                            </div>
                        </x-modal>
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
                                @foreach($movementTypes as $type)
                                    <option
                                        value="{{$type->id}}"
                                    >{{$type->name}}</option>
                                @endforeach
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
                                min="1"
                                max="{{
                                    $product->latestBalance->amount > 65000
                                    ? 65000 : $product->latestBalance->amount
                                }}"
                                step="1" required
                                x-model="amount"
                                x-on:keyup="$dispatch('changed-movement-input-value')"
                                x-on:change="$dispatch('changed-movement-input-value')"
                            />
                        </div>
                    </x-table.td>
                    <x-table.td
                        class="col-span-1 lg:text-center  flex items-center lg:table-cell"
                    >
                        <div class="flex items-center lg:justify-center">
                            <label for="unitarySalePriceInput{{$product->id}}" class="mr-1 lg:hidden">
                                Precio&nbsp;&nbsp;&nbsp;
                            </label>
                            $<select
                                name="unitary_sale_prices[]"
                                class="
                                    w-48 lg:w-20
                                    p-1 pr-8
                                    border-gray-300
                                    focus:border-indigo-500 focus:ring-indigo-500
                                    rounded-md shadow-sm
                                "
                                id="unitarySalePriceInput{{$product->id}}"
                                x-on:change="$dispatch('changed-movement-input-value')"
                            >
                                @foreach($product->salePrices->sortBy('units_number') as $salePrice)
                                    <option
                                        @if(!$loop->first)
                                            class="hidden"
                                        @endif
                                        data-units-number="{{$salePrice->units_number}}"
                                        id="unitarySalePrice{{$salePrice->id}}"
                                        value="{{$salePrice->id}}"
                                    >{{$salePrice->price}}</option>
                                @endforeach
                            </select>
                        </div>
                    </x-table.td>
                    <x-table.td
                        class="col-span-1 lg:text-center  flex items-center lg:table-cell"
                    >
                        <div class="flex items-center lg:justify-center">
                            <div x-text="totalPrice" class="hidden total-sale-price-input"></div>
                            <label class="mr-1 lg:hidden" for="totalSalePriceInput{{$product->id}}">
                                Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </label>
                            $<x-text-input
                                type="text"
                                class="w-48 lg:w-20" readonly
                                id="totalSalePriceInput{{$product->id}}"
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
                                $dispatch('removed-movement-input');
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
                            document.querySelectorAll('.total-sale-price-input')
                        );
                        let summation = 0;
                        for(const input of inputs){
                            summation += parseFloat(input.textContent);
                        }
                        const formater = $store.priceFormater.create();
                        this.displayTotal = `$${formater.format(summation)}`;
                    },
                    init() {
                        this.synchronizeTotal();
                    }
                }"
                x-on:changed-movement-input-value.window.debounce="synchronizeTotal"
                x-on:removed-movement-input.window.debounce.400ms="synchronizeTotal"
                x-on:added-movement-input.window.debounce.400ms="synchronizeTotal"
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
    <x-input-label for="searchProductInput" :required="true">
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
                <x-table.th>
                    Disponible
                </x-table.th>
                <x-table.th></x-table.th>
            </x-table.tr>
        </x-slot:head>
        <x-slot:body>
            @foreach($searchedProducts as $product)
                <x-table.tr x-data wire:key="{{$product->id}}">
                    <x-table.td>
                        {{$product->tag}}
                    </x-table.td>
                    @if(
                        !$product->started_inventory
                        || ($product->latestBalance?->amount === 0)
                        || ($product->latestBalance?->amount === null)
                    )
                        <x-table.td class="text-center">
                            <span class="text-red-500">0</span>
                        </x-table.td>
                        <x-table.td>
                            <x-secondary-button
                                disabled
                                class="opacity-50"
                            >
                                <x-icons.take-hand class="w-5 h-5" />
                            </x-secondary-button>
                        </x-table.td>
                    @else
                        <x-table.td class="text-center text-red-500">
                            {{$product->latestBalance->amount}}
                        </x-table.td>
                        <x-table.td>
                            <x-secondary-button
                                x-on:click="
                                    $wire.addProduct({{$product->id}})
                                    $dispatch('added-movement-input');
                                "
                            >
                                <x-icons.take-hand class="w-5 h-5" />
                            </x-secondary-button>
                        </x-table.td>
                    @endif
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
    <div class="max-w-sm">
        {{$searchedProducts->links(data: ['scrollTo' => false])}}
    </div>
@endif

<x-input-error
    :messages="$errors->get('products')"
/>
@foreach($errors->get('products.*') as $error)
    <x-input-error :messages="$error"/>
@endforeach
<x-input-error
    :messages="$errors->get('amounts')"
/>
@foreach($errors->get('amounts.*') as $error)
    <x-input-error :messages="$error"/>
@endforeach
<x-input-error
    :messages="$errors->get('movement_types')"
/>
@foreach($errors->get('movement_types.*') as $error)
    <x-input-error :messages="$error"/>
@endforeach
<x-input-error
    :messages="$errors->get('unitary_sale_prices')"
/>
@foreach($errors->get('unitary_sale_prices.*') as $error)
    <x-input-error :messages="$error"/>
@endforeach

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

    Alpine.data('movementInput', (initialSalePrice, productId) => {
        return {
            amount: 1,
            productId: productId,
            unitaryPrice: initialSalePrice,
            totalPrice: 0.00,
            displayTotalPrice: '0.00',
            formater: null,
            init() {
                this.formater = $store.priceFormater.create();
                this.totalPrice = (this.amount * this.unitaryPrice).toFixed(2);
                this.displayTotalPrice = this.formater.format(this.totalPrice);
            },
            synchronizeValues() {
                this.unitaryPrice = this.selectedSalePrice();
                this.totalPrice = (this.amount * this.unitaryPrice).toFixed(2);
                this.displayTotalPrice = this.formater.format(this.totalPrice);
            },
            selectedSalePrice() {
                const unitaryPriceInput = this.findUnitarySalePriceInput();
                let salePrice = unitaryPriceInput.selectedOptions.item(0).textContent;
                salePrice = parseFloat(salePrice);
                return salePrice;
            },
            updateSalePrices() {
                const unitarySalePriceInput = this.findUnitarySalePriceInput(),
                      options = Array.from(unitarySalePriceInput.options);
                for(let option of options){
                    let amount = parseInt(this.amount);
                    amount = Number.isNaN(amount) || (amount === 0)
                        ? 1 : amount;
                    if(!(amount >= parseInt(option.dataset.unitsNumber))){
                        option.classList.add('hidden');
                        option.selected = false;
                        options[0].selected = true;
                    } else {
                        option.classList.remove('hidden');
                    }
                }
            },
            // General utilities
            findUnitarySalePriceInput() {
                return document.getElementById(`unitarySalePriceInput${this.productId}`);
            }
        }
    });
</script>
@endscript

</div>
