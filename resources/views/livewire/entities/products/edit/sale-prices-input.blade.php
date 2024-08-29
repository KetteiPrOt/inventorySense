<div>
    <x-table class="max-w-sm">
        <x-slot:head>
            <x-table.tr :hover="false">
                <x-table.th>
                    Desde (unidades)
                </x-table.th>
                <x-table.th>
                    Precio ($)
                </x-table.th>
            </x-table.tr>
        </x-slot:head>
        <x-slot:body>
            {{-- Sale Price Input Template --}}
            <x-table.tr
                id="salePriceInputTemplate" class="hidden"
            >
                <x-table.td>
                    <x-number-input
                        class="units-number-input w-4/5 sm:w-2/3"
                        min="1" max="65000"
                        step="1"
                    />
                </x-table.td>
                <x-table.td>
                    $
                    <x-number-input
                        class="sale-price-input w-4/5"
                        min="0.000001" max="9999.999999"
                        step="0.000001"
                    />
                </x-table.td>
            </x-table.tr>
            @foreach($salePrices as $salePrice)
                @if($loop->first)
                    {{-- Default Sale Price Input --}}
                    <x-table.tr
                        id="defaultSalePriceInput"
                    >
                        <x-table.td>
                            <x-number-input
                                class="units-number-input w-4/5 sm:w-2/3"
                                value="{{$salePrice->units_number}}"
                                readonly required
                                name="units_numbers[]"
                            />
                        </x-table.td>
                        <x-table.td>
                            $
                            <x-number-input
                                class="sale-price-input w-4/5"
                                value="{{$salePrice->price}}"
                                required
                                min="0.000001" max="9999.999999"
                                step="0.000001"
                                name="sale_prices[]"
                            />
                        </x-table.td>
                    </x-table.tr>
                @else
                    {{-- Others Sale Price Input --}}
                    <x-table.tr>
                        <x-table.td>
                            <x-number-input
                                class="units-number-input w-4/5 sm:w-2/3"
                                value="{{$salePrice->units_number}}"
                                min="1" max="65000"
                                step="1"
                                required
                                name="units_numbers[]"
                            />
                        </x-table.td>
                        <x-table.td>
                            $
                            <x-number-input
                                class="sale-price-input w-4/5"
                                value="{{$salePrice->price}}"
                                min="0.000001" max="9999.999999"
                                step="0.000001"
                                required
                                name="sale_prices[]"
                            />
                        </x-table.td>
                    </x-table.tr>
                @endif
            @endforeach
            {{-- Sale Price Input Command --}}
            <x-table.tr
                id="salePriceInputCommand"
            >
                <x-table.td>
                    <x-secondary-button
                        class="bg-green-500 hover:bg-green-400"
                        id="salePriceInputAddBtn"
                        wire:click="pushSalePriceInput()"
                    >
                        <x-icons.plus color="#fff" class="w-3 h-3" />
                    </x-secondary-button>
                    <x-secondary-button
                        class="bg-red-500 hover:bg-red-400"
                        id="salePriceInputRemoveBtn"
                        wire:click="popSalePriceInput()"
                    >
                        <x-icons.plus color="#fff" class="w-3 h-3 rotate-45" />
                    </x-secondary-button>
                </x-table.td>
                <x-table.td></x-table.td>
            </x-table.tr>
        </x-slot:body>
    </x-table>

    {{-- Input Errors --}}
    <x-input-error :messages="$errors->get('units_numbers')" />

    @foreach($errors->get('units_numbers.*') as $error)
        <x-input-error :messages="$error" />
    @endforeach

    <x-input-error :messages="$errors->get('sale_prices')" />

    @foreach($errors->get('sale_prices.*') as $error)
        <x-input-error :messages="$error" />
    @endforeach

    <span class="text-red-500" id="salePricesInputError"></span>

    @script
    <script>
        document.getElementById('submitButton').addEventListener('click', (event) => {
            const errorElement = document.getElementById('salePricesInputError');
            const unitsNumberInputs = Array.from(
                document.querySelectorAll('.units-number-input')
            );
            const salePriceInputs = Array.from(
                document.querySelectorAll('.sale-price-input')
            );
            // Remove hidden templates
            unitsNumberInputs.shift();
            salePriceInputs.shift();
            // Map arrays of input values
            const salePrices = salePriceInputs.map((element) => parseFloat(element.value));
            // Validate unique values in each array
            const uniqueArrayElements = (array) => {
                let uniqueElements = true;
                for(element of array){
                    if(
                        array.indexOf(element)
                        != array.lastIndexOf(element)
                    ){
                        uniqueElements = false;
                        break;
                    }
                }
                return uniqueElements;
            }
            const uniqueSalePrices = uniqueArrayElements(salePrices);
            // Show error messages if is need
            if( ! uniqueSalePrices ){
                errorElement.textContent = 'Los precios de venta deben ser diferentes.';
                event.preventDefault();
            }
        });
    </script>
    @endscript
</div>
