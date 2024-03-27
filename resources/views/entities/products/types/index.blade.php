<x-layouts.primary
    header="Tipos de producto"
>
    <div class="flex justify-center sm:justify-start">
        <x-secondary-button
            x-data
            x-on:click.prevent="$dispatch('open-modal', 'create-type')"
        >
            Crear nuevo
        </x-secondary-button>
        <x-modal name="create-type">
            <livewire:entities.products.types.create.main
                :page="request()->query('page')"
                :search="request()->query('search')"
            />
        </x-modal>
    </div>

    <form class="mb-4">
        <x-text-input
            name="search"
            class="mt-4"
            minlength="2"
            maxlength="49"
            value="{{request()->query('search')}}"
        />
        <x-primary-button class="mt-1">
            Buscar
        </x-primary-button>
        <x-input-error
            :messages="$errors->get('search')"
        />
    </form>

    <x-table class="max-w-sm mb-1">
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
                <x-table.tr>
                    <x-table.td>
                        {{$type->name}}
                    </x-table.td>
                    <x-table.td>
                        <div class="w-full flex justify-around">
                            <livewire:entities.products.types.edit.main
                                :key="$type->id"
                                :$type
                                :page="request()->query('page')"
                                :search="request()->query('search')"
                            />
                            <button
                                x-data class="w-7 h-7 focus:outline-offset-4 focus:outline-red-500"
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-type-{{$type->id}}-deletion')"
                            >
                                <x-icons.delete class="w-full h-full cursor-pointer" color="#EF4444" />
                            </button>
                            <x-modal name="confirm-type-{{$type->id}}-deletion">
                                <form
                                    action="{{route('product-types.destroy', $type->id)}}"
                                    method="post" class="p-6"
                                >
                                    @csrf
                                    @method('delete')

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

                                        <x-danger-button class="ms-3">
                                            Eliminar
                                        </x-danger-button>
                                    </div>
                                </form>
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
    <div class="max-w-sm">
        {{$types->links()}}
    </div>


</x-layouts.primary>
