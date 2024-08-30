<x-layouts.primary
    header="Ver bodega"
>
    <div class="sm:flex mb-6">
        <div class="mb-4 sm:mb-0 sm:mr-12">
            <p>
                <strong>Bodega</strong> <br>
                {{$warehouse->name}}
            </p>
        </div>
        <div>
            <p>
                <strong>Creada el</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($warehouse->created_at));
                }}
            </p>
            <p>
                <strong>Ultima actualización</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($warehouse->updated_at));
                }}
            </p>
        </div>
    </div>

    <div>
        <x-secondary-link-button
            :href="route('warehouses.edit', $warehouse->id)"
        >
            Editar
        </x-secondary-link-button>
        {{-- <x-danger-button
            x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-warehouse-deletion')"
        >Eliminar</x-danger-button>

        <x-modal name="confirm-warehouse-deletion" :show="$errors->isNotEmpty()" focusable>
            <form
                action="{{route('warehouses.destroy', $warehouse->id)}}"
                method="post"
                class="p-6"
            >
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que deseas eliminar la bodega?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez que la bodega sea eliminada se moverá a la papelera de reciclaje.
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Cancelar
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        Eliminar Bodega
                    </x-danger-button>
                </div>
            </form>
        </x-modal> --}}
    </div>

</x-layouts.primary>
