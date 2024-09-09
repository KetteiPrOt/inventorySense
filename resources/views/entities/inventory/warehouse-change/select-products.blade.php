<x-layouts.primary
    header="Cambio de bodega"
>
    <x-toast.simple-success
        :show="session('success')"
    />

    <form action="{{route('warehouse-change.change')}}" method="POST">
        @csrf

        <section class="space-y-4">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Bodegas seleccionadas
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                    Mover los productos...
                </p>
            </header>

            <div>
                <x-input-label
                    for="fromWarehouseInput"
                    :value="'Desde'"
                    :required="true"
                />
                <x-select-input name="from_warehouse" id="fromWarehouseInput">
                    <option value="{{$warehouses['from']->id}}">
                        {{$warehouses['from']->name}}
                    </option>
                </x-select-input>
                <x-secondary-link-button
                    :href="route('warehouse-change.select-warehouses')"
                >Cambiar</x-secondary-link-button>
                <x-input-error
                    :messages="$errors->get('from_warehouse')"
                />
            </div>

            <div>
                <x-input-label
                    for="toWarehouseInput"
                    :value="'Hasta'"
                    :required="true"
                />
                <x-select-input name="to_warehouse" id="toWarehouseInput">
                    <option value="{{$warehouses['to']->id}}">
                        {{$warehouses['to']->name}}
                    </option>
                </x-select-input>
                <x-secondary-link-button
                    :href="route('warehouse-change.select-warehouses')"
                >Cambiar</x-secondary-link-button>
                <x-input-error
                    :messages="$errors->get('to_warehouse')"
                />
            </div>
        </section>

        <section class="space-y-4 mt-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Seleccionar productos
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                    Busque, seleccione los productos, y especifique la cantidad que desea movilizar.
                </p>
            </header>

            <div>
                <livewire:entities.inventory.warehouse-change.products-input :from-warehouse="$warehouses['from']->id" />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button>
                Mover
            </x-primary-button>
        </div>
    </form>
</x-layouts.primary>