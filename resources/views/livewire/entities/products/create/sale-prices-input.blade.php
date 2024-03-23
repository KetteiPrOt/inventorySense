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
                        min="2" max="65000"
                        step="1"
                    />
                </x-table.td>
                <x-table.td>
                    $
                    <x-number-input
                        class="sale-price-input w-4/5"
                        min="0.01" max="9999.99"
                        step="0.01"
                    />
                </x-table.td>
            </x-table.tr>
            <x-table.tr
                id="defaultSalePriceInput"
            >
                <x-table.td>
                    <x-number-input
                        class="units-number-input w-4/5 sm:w-2/3"
                        value="1"
                        readonly required
                        name="units_numbers[]"
                    />
                </x-table.td>
                <x-table.td>
                    $
                    <x-number-input
                        class="sale-price-input w-4/5"
                        required
                        min="0.01" max="9999.99"
                        step="0.01"
                        name="sale_prices[]"
                    />
                </x-table.td>
            </x-table.tr>
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

    <x-input-error
        :messages="$errors->get('units_numbers')"
    />

    @foreach($errors->get('units_numbers.*') as $error)
        <x-input-error
            :messages="$error"
        />
    @endforeach

    <x-input-error
        :messages="$errors->get('sale_prices')"
    />

    @foreach($errors->get('sale_prices.*') as $error)
        <x-input-error
            :messages="$error"
        />
    @endforeach

    <span class="text-red-500" id="salePricesInputError"></span>

    @script
    <script>
        document.getElementById('submitButton').addEventListener('click', (event) => {
            const errorElement = document.getElementById('salePricesInputError');
            const unitsNumberInputs = Array.from(
                      document.querySelectorAll('.units-number-input')
                  ),
                  salePriceInputs = Array.from(
                      document.querySelectorAll('.sale-price-input')
                  );
            // Remove hidden templates
            unitsNumberInputs.shift();
            salePriceInputs.shift();
            // Map arrays of input values
            const unitsNumbers = unitsNumberInputs.map((element) => {
                      return element.value;
                  }),
                  salePrices = salePriceInputs.map((element) => {
                      return element.value;
                  });
            // Validate unique values in each array
            const uniqueArrayElements = (array) => {
                let uniqueElements = true;
                for(element of array){
                    if(
                        array.indexOf(element)
                        !== array.lastIndexOf(element)
                    ){
                        uniqueElements = false;
                        break;
                    }
                }
                return uniqueElements;
            }
            const uniqueUnitsNumbers = uniqueArrayElements(unitsNumbers),
                  uniqueSalePrices = uniqueArrayElements(salePrices);
            // Show error messages if is need
            if(!uniqueUnitsNumbers || !uniqueSalePrices){
                errorElement.textContent = (!uniqueUnitsNumbers)
                    ? 'Las unidades deben ser diferentes en cada precio.'
                    : 'Los precios de venta deben ser diferentes.';
                // event.preventDefault();
            }
        });
    </script>
    @endscript
</div>
