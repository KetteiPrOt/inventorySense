<div>
    <form wire:submit="create" class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Crear presentación
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Especifique el contenido en mililitros (ml) de la nueva presentación de producto.
        </p>

        <div>
            <div class="mt-1 flex w-full">
                <x-number-input
                    class="block w-full"
                    required min="1" max="65000" step="1"
                    wire:model="content"
                    placeholder="200..."
                />
                <div class="text-lg font-bold pl-1 flex items-center">
                    ml
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('content')" />
        </div>

        <div class="flex items-end mt-4">
            <x-primary-button class="mr-1">
                Guardar
            </x-primary-button>

            <x-action-message class="ml-1" on="presentation-created" color="text-green-400">
                Guardado.
            </x-action-message>
        </div>
    </form>
</div>
