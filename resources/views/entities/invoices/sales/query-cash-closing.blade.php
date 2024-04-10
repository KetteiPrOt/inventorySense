<x-layouts.primary
    header="Consultar cierre de caja"
>
    <form action="{{route('sales.cash-closing')}}">
        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Detalles de consulta
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Opcionalmente especifique el intervalo de consulta y seleccione la bodega @can('users'), el vendedor, @endcan y el producto para filtar en el cierre de caja.
                </p>
            </header>

            <div class="sm:flex flex-wrap">
                <div class="mb-6 sm:mb-0 sm:mr-6">
                    <x-input-label for="dateFromInput" :required="true">
                        Desde
                    </x-input-label>
                    <x-date-input
                        required
                        id="dateFromInput"
                        name="date_from"
                        value="{{old('date_from', date('Y-m-d'))}}"
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

            <div>
                <livewire:entities.warehouses.index.choose
                    :selected-by-default="old('warehouse', session('sales-selected-warehouse'))"
                    :required="false"
                />
            </div>

            @can('users')
                <div>
                    <livewire:entities.users.index.choose
                        :selected-by-default="auth()->user()->id"
                        :required="false"
                    />
                </div>
            @endcan

            <div>
                <livewire:entities.products.index.choose
                    :required="false"
                    :show-all-by-default="false"
                    :selected-by-default="old('product')"
                />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button>
                Consultar
            </x-primary-button>
        </div>
    </form>
</x-layouts.primary>
