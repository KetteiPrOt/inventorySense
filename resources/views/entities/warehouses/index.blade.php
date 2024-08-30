<x-layouts.primary
    header="Bodegas"
>
    <div class="flex flex-col items-center sm:items-start">
        <x-secondary-link-button
            :href="route('warehouses.create')"
            class="mb-4"
        >
            Crear nueva
        </x-secondary-link-button>

        <form
            class="mb-4 flex flex-col items-center sm:block"
            action="{{request()->url()}}"
        >
            <x-text-input
                name="search" minlength="2" maxlength="255"
                value="{{request()->query('search')}}"
            />
            <x-input-error :messages="$errors->get('search')" />
            <x-primary-button class="mt-1 sm:mt-0">
                Buscar
            </x-primary-button>
        </form>
    </div>

    <x-table class="mb-1 max-w-sm">
        <x-slot:head>
            <x-table.tr :hover="false">
                <x-table.th>
                    N.
                </x-table.th>
                <x-table.th>
                    <x-icons.order
                        :data="[
                            'column' => 'name',
                            'currentColumn' => $filters['column'],
                            'order' => $filters['order'],
                            'route' => 'warehouses.index'
                        ]"
                    >Nombre</x-icons.order>
                </x-table.th>
            </x-table.tr>
        </x-slot:head>
        <x-slot:body>
            @foreach($warehouses as $warehouse)
                <x-table.tr>
                    <x-table.td>
                        {{$warehouse->n}}
                    </x-table.td>
                    <x-table.td>
                        <a href="{{route('warehouses.show', $warehouse->id)}}">
                            {{$warehouse->name}}
                        </a>
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
    {{$warehouses->links()}}
    @if($warehouses->isEmpty())
        <p class="text-red-500">
            No se encontraron bodegas...
        </p>
    @endif
</x-layouts.primary>
