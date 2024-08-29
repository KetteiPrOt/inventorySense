<x-layouts.primary
    header="Crear compra"
>
    <form action="{{route('purchases.store')}}" method="post" name="createPurchase">
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Información de la compra
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Seleccione la bodega, el proveedor, especifique el número de factura, comentario, plazo de pago (opcional), y los productos de la compra para crearla.
                </p>
            </header>

            <div>
                <livewire:entities.warehouses.index.choose
                    :selected-by-default="old('warehouse', session('purchases-selected-warehouse'))"
                />
            </div>

            <div
                x-data="{ invalidNumber: false }"
                x-on:invalid-invoice-number-input.window="
                    invalidNumber = true; $el.scrollIntoView({ behavior: 'smooth' });
                "
                x-on:valid-invoice-number-input.window="invalidNumber = false"
            >
                <x-input-label for="invoiceNumberInput" :required="false">
                    Número de factura
                </x-input-label>
                <x-text-input
                    x-data
                    x-mask="999-999-999999999" placeholder="000-000-000000000"
                    id="invoiceNumberInput" name="number" 
                    class="mt-1 block w-full max-w-sm"
                />
                <x-input-error class="mt-2" :messages="$errors->get('number')" />
                <span x-show="invalidNumber" class="text-red-500 block">
                    El número de factura esta incompleto.
                </span>
            </div>

            <div>
                <livewire:entities.providers.index.choose
                    :required="false"
                    :show-all-by-default="false"
                />
            </div>

            <div
                x-data="{
                    open: false,
                    invalidDate: false
                }"
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
                        >Compra ya pagada.</label>
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
                    Productos comprados
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Seleccione los productos, especificando la cantidad y precio de compra.
                </p>
            </header>

            <div>
                <livewire:entities.invoices.purchases.create.movements-input />
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
            let valid;
            let productsInput = document.forms.createPurchase.elements['products[]'];
            if((typeof productsInput) === 'undefined'){
                $event.preventDefault();
                $dispatch('invalid-input', {message: 'Selecciona al menos un producto...'});
            } else {
                $dispatch('valid-input');
            }
            valid = duePaymentInput();
            if(!valid){
                $event.preventDefault();
                $dispatch('invalid-due-payment-date-input');
            } else {
                $dispatch('valid-due-payment-date-input');
            }
            valid = invoiceNumberInput();
            if(!valid){
                $event.preventDefault();
                $dispatch('invalid-invoice-number-input');
            } else {
                $dispatch('valid-invoice-number-input');
            }
        };

        const invoiceNumberInput = () => {
            const input = document.getElementById('invoiceNumberInput');
            return (input.value.length == 17) || (input.value.length == 0);
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
