<x-layouts.primary
    header="Cambio de bodega"
>
    <form action="{{route('warehouse-change.select-products')}}">
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Seleccionar Bodegas
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                    Para mover los productos...
                </p>
            </header>

            <div>
                <livewire:entities.warehouses.index.choose
                    :selected-by-default="old('from_warehouse')"
                    :show-all-by-default="true"
                    :required="true"
                    :input-label="'Desde:'"
                    :input-name="'from_warehouse'"
                />
            </div>

            <div>
                <livewire:entities.warehouses.index.choose
                    :selected-by-default="old('to_warehouse')"
                    :show-all-by-default="true"
                    :required="true"
                    :input-label="'Hacia:'"
                    :input-name="'to_warehouse'"
                />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button>
                Continuar
            </x-primary-button>
        </div>
    </form>
</x-layouts.primary>