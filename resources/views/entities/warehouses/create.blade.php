<x-layouts.primary
    header="Crear bodega"
>
    <form action="{{route('warehouses.store')}}" method="post">
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Información de la bodega
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Especifique el nombre de la bodega para crearla.
                </p>
            </header>

            <div>
                <x-input-label for="nameInput" :required="true">
                    Nombre
                </x-input-label>
                <x-text-input
                    required
                    id="nameInput" name="name" 
                    class="mt-1 block w-full max-w-sm"
                    minlength="2" maxlength="255"
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button id="submitButton" class="mr-1">
                Guardar
            </x-primary-button>

            <x-secondary-link-button :href="route('warehouses.index')" class="ml-1">
                Cancelar
            </x-secondary-link-button>
        </div>
    </form>
</x-layouts.primary>
