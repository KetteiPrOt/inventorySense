<div>
    <button
        x-data class="w-6 h-6 focus:outline-offset-4 focus:outline-black"
        x-on:click="$dispatch('open-modal', 'edit-presentation-{{$presentation->id}}')"
    >
        <x-icons.edit class="w-full h-full cursor-pointer" />
    </button>
    <x-modal name="edit-presentation-{{$presentation->id}}">
        <form wire:submit="update" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Editar presentación
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Actualize el contenido en mililitros (ml) de la presentación de producto.
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

                <x-action-message class="ml-1" on="presentation-updated" color="text-green-400">
                    Guardado.
                </x-action-message>
            </div>
        </form>
    </x-modal>
</div>
