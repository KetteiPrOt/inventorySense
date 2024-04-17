<x-layouts.primary
    header="Consultar inventario"
>
    <form action="{{route('inventory.index')}}">
        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Detalles de consulta
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                    Opcionalmemnte especifique una bodega donde consultar el inventario.
                </p>
            </header>

            <div>
                <livewire:entities.warehouses.index.choose
                    :required="false"
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
