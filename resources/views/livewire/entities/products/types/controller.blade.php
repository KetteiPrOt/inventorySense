<div>
    <x-secondary-button
        x-data
        x-on:click.prevent="$dispatch('open-modal', 'create-type')"
    >
        Crear nuevo
    </x-secondary-button>
    <x-modal name="create-type">
        <livewire:entities.products.types.create.main />
    </x-modal>

    <x-text-input
        wire:model.live="search"
        class="block mt-4"
        placeholder="Buscar..."
        maxlength="49"
    />

    <x-table class="mt-4 max-w-sm">
        <x-slot:head>
            <x-table.tr :hover="false">
                <x-table.th>
                    Nombre
                </x-table.th>
                <x-table.th></x-table.th>
            </x-table.tr>
        </x-slot:head>
        <x-slot:body>
            @forelse($types as $type)
                <x-table.tr wire:key="{{$type->id}}">
                    <x-table.td>
                        {{$type->name}}
                    </x-table.td>
                    <x-table.td>
                        <div class="w-full flex justify-around">
                            <x-icons.edit
                                class="w-6 h-6 cursor-pointer"
                                x-data
                                x-on:click.prevent="$dispatch('open-modal', 'edit-type-{{$type->id}}')"
                            />
                            <x-modal name="edit-type-{{$type->id}}">
                                <livewire:entities.products.types.edit.main
                                    :key="$type->id"
                                    :$type
                                />
                            </x-modal>
                            <x-icons.delete
                                x-data
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-type-{{$type->id}}-deletion')"
                                class="w-7 h-7 cursor-pointer" color="#EF4444"
                            />
                            <x-modal name="confirm-type-{{$type->id}}-deletion">
                                <div class="p-6">
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        ¿Seguro que deseas eliminar el tipo de producto?
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        Una vez que el tipo de producto sea eliminado se perderá <strong>por siempre y de forma irreversible</strong>. Además los productos asociados ya no lo estarán, quedando como productos sin tipo.
                                    </p>

                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click="$dispatch('close')">
                                            Cancelar
                                        </x-secondary-button>

                                        <x-danger-button
                                            class="ms-3"
                                            x-on:click="$wire.delete({{$type->id}});"
                                        >
                                            Eliminar Tipo
                                        </x-danger-button>
                                    </div>
                                </div>
                            </x-modal>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td>
                        <span class="text-red-500">
                            No se encontraron tipos de producto...
                        </span>
                    </x-table.td>
                    <x-table.td></x-table.td>
                </x-table.tr>
            @endforelse
        </x-slot:body>
    </x-table>
    <div class="max-w-sm mt-1">
        {{$types->links(data: ['scrollTo' => false])}}
    </div>
    @script
    <script>
        $wire.on('type-updated', () => $wire.$refresh());
        $wire.on('type-created', () => $wire.$refresh());
    </script>
    @endscript
</div>
