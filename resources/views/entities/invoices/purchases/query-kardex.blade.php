<x-layouts.primary
    header="Consultar kardex"
>
    <form action="{{route('purchases.kardex')}}">
        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Detalles de consulta
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Seleccione un producto, bodega, y especifique el intervalo de consulta para ver el kardex.
                </p>
            </header>

            <div>
                <livewire:entities.products.index.choose
                    :selected-by-default="old('product')"
                    :show-all-by-default="false"
                />
            </div>

            <div>
                <livewire:entities.warehouses.index.choose
                    :selected-by-default="old('warehouse')"
                    :show-all-by-default="false"
                    :required="false"
                />
            </div>

            <div class="sm:flex flex-wrap">
                <div class="mb-6 sm:mb-0 sm:mr-6">
                    @php
                        $lastMonth = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
                    @endphp
                    <x-input-label for="dateFromInput" :required="true">
                        Desde
                    </x-input-label>
                    <x-date-input
                        required
                        id="dateFromInput"
                        name="date_from"
                        value="{{old('date_from', date('Y-m-d', $lastMonth))}}"
                        max="{{date('Y-m-d')}}"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('date_from')" />
                </div>

                <div>
                    <x-input-label for="dateToInput" :required="true">
                        Hasta
                    </x-input-label>
                    <x-date-input
                        required
                        id="dateToInput"
                        name="date_to"
                        value="{{old('date_to', date('Y-m-d'))}}"
                        max="{{date('Y-m-d')}}"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('date_to')" />
                </div>
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button>
                Consultar
            </x-primary-button>
        </div>
    </form>
</x-layouts.primary>
