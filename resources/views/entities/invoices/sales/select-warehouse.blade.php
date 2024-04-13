<x-layouts.primary
    header="Seleccionar bodega de ventas"
>
    <form action="{{route('sales.save-selected-warehouse')}}" method="post">
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Bodega de ventas
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Seleccione la bodega donde realizará las ventas. Luego podrá cambiarla en cualquier momento que desee.
                </p>
            </header>

            <div>
                <livewire:entities.warehouses.index.choose
                    :selected-by-default="old('warehouse', session('sales-selected-warehouse'))"
                />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button>
                Guardar
            </x-primary-button>
        </div>
    </form>
</x-layouts.primary>