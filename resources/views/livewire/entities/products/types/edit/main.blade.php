<div>
    <button
        x-data class="w-6 h-6 focus:outline-offset-4 focus:outline-black"
        x-on:click="$dispatch('open-modal', 'edit-type-{{$type->id}}')"
    >
        <x-icons.edit class="w-full h-full cursor-pointer" />
    </button>
    <x-modal name="edit-type-{{$type->id}}">
        <form wire:submit="update" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Editar tipo
            </h2>
    
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Actualize el nombre del tipo de producto.
            </p>
    
            <div>
                <x-text-input
                    class="mt-1 block w-full"
                    required minlength="2" maxlength="49"
                    wire:model="name"
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
    
            <div class="flex items-end mt-4">
                <x-primary-button class="mr-1">
                    Guardar
                </x-primary-button>
    
                <x-action-message class="ml-1" on="type-updated" color="text-green-400">
                    Guardado.
                </x-action-message>
            </div>
        </form>
    </x-modal>
</div>
