<x-layouts.primary
    header="Crear venta"
>
    <form action="{{route('sales.store')}}" method="post" name="createSale">
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Información de la venta
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Seleccione la bodega, el cliente, especifique opcionalmente algún comentario, plazo de pago, y los productos de la venta para crearla.
                </p>
            </header>

            <div>
                <x-input-label for="warehouseInput" :required="true">
                    Bodega
                </x-input-label>
                <x-select-input
                    name="warehouse"
                    id="warehouseInput"
                    class="mb-2 block w-full max-w-sm"
                >
                    <option selected value="{{$warehouse->id}}">
                        {{$warehouse->name}}
                    </option>
                </x-select-input>
                <x-secondary-link-button href="{{route('sales.select-warehouse')}}">
                    Cambiar
                </x-secondary-link-button>
                <x-input-error class="mt-2" :messages="$errors->get('warehouse')" />
            </div>

            <div>
                <livewire:entities.clients.index.choose
                    :required="false"
                />
            </div>

            <div
                x-data="{open: false, invalidDate: false}"
                x-on:invalid-due-payment-date-input.window="
                    $el.scrollIntoView({ behavior: 'smooth' }); open = true; invalidDate = true;
                "
                x-on:valid-due-payment-date-input.window="invalidDate = false"
            >
                <div class="mb-6">
                    <x-input-label for="paidInput">
                        Pagada
                    </x-input-label>
                    <div class="flex items-center">
                        <input
                            id="paidInput" class="rounded mr-2"
                            type="checkbox" name="paid"
                            checked
                            x-on:change="
                                open = $event.target.checked
                                    ? open = false
                                    : open = true;
                            "
                        />
                        <label
                            for="paidInput"
                            class="text-sm text-gray-600"
                        >Venta ya pagada.</label>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('paid')" />
                </div>

                <div x-show="open">
                    <x-input-label for="duePaymentDateInput" :required="true">
                        Vencimiento del pago
                    </x-input-label>
                    <div class="flex items-center">
                        @php
                            $tomorrow = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
                            $nextMonth = mktime(0, 0, 0, date("m") + 1, date("d"), date("Y"));
                        @endphp
                        <x-date-input
                            required
                            id="duePaymentDateInput"
                            name="due_payment_date"
                            value="{{old('due_payment_date', date('Y-m-d', $nextMonth))}}"
                            min="{{date('Y-m-d', $tomorrow)}}"
                            max="{{date('Y-m-d', $nextMonth)}}"
                        />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('due_payment_date')" />
                    <span x-show="invalidDate" class="block text-red-500">
                        La fecha no es válida. Debe ser menor al próximo mes y mayor a hoy.
                    </span>
                </div>
            </div>

            <div>
                <x-input-label for="commentInput">
                    Comentario
                </x-input-label>
                <x-textarea-input
                    id="commentInput" name="comment" 
                    class="mt-1 block w-full max-w-sm min-h-10"
                    minlength="2" maxlength="1000"
                >{{old('comment')}}</x-textarea-input>
                <x-input-error class="mt-2" :messages="$errors->get('comment')" />
            </div>
        </section>

        <section class="space-y-6 mt-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Productos vendidos
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Seleccione los productos, especificando la cantidad y opcionalmente algún precio de oferta.
                </p>
            </header>

            <div>
                <livewire:entities.invoices.sales.create.movements-input
                    :warehouse-id="session('sales-selected-warehouse')"
                />
            </div>
        </section>

        <div
            x-data="{error: false, message: ''}"
            x-on:invalid-input="error = true; message = $event.detail.message"
            x-on:valid-input="error = false; message = ''"
            class="flex flex-col items-center justify-end sm:items-start mt-6"
        >
            <x-primary-button
                x-on:click="validate($event, $dispatch)"
                id="submitButton" class="mr-1"
            >
                Guardar
            </x-primary-button>
            <span
                x-show="error" x-text="message" class="text-red-500 inline-block mt-1"
            ></span>
        </div>
    </form>

    <script>
        const validate = ($event, $dispatch) => {
            let productsInput = document.forms.createSale.elements['products[]'];
            if((typeof productsInput) === 'undefined'){
                $event.preventDefault();
                $dispatch('invalid-input', {message: 'Selecciona al menos un producto...'});
            } else {
                $dispatch('valid-input');
            }
            let valid = duePaymentInput();
            if(!valid){
                $event.preventDefault();
                $dispatch('invalid-due-payment-date-input');
            } else {
                $dispatch('valid-due-payment-date-input');
            }
        };

        const duePaymentInput = () => {
            let valid;
            const date = new Date(),
                  inputElement = document.getElementById('duePaymentDateInput'),
                  nextMonth = generateNextMonthDate(),
                  tomorrow = generateTomorrowDate();
            let input = Date.parse(`${inputElement.value}T00:00:00`);
            date.setTime(input);
            if(
                (date.getTime() < tomorrow.getTime())
                || (date.getTime() > nextMonth.getTime())
            ){
                valid = false;
            } else {
                valid = true;
            }
            return valid;
        };

        const generateNextMonthDate = () => {
            const date = new Date();
            let year = date.getFullYear(),
                month = date.getMonth();
            month++;
            if(month > 11){
                month = 1;
                year++;
            }
            date.setFullYear(year);
            date.setMonth(month);
            date.setHours(0, 0, 0);
            date.setMilliseconds(0);
            return date;
        };

        const generateTomorrowDate = () => {
            const date = new Date();
            let tomorrow = date.getTime() + (24 * 60 * 60 * 1000);
            date.setTime(tomorrow);
            date.setHours(0, 0, 0);
            date.setMilliseconds(0);
            return date;
        };
    </script>
</x-layouts.primary>
