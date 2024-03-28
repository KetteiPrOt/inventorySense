<x-layouts.primary
    header="Proveedores"
>

    <div class="flex flex-col items-center sm:items-start">
        <x-secondary-link-button
            :href="route('providers.create')"
            class="mb-4"
        >
            Crear nuevo
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

    <x-table class="mb-1">
        <x-slot:head>
            <x-table.tr :hover="false">
                <x-table.th>N.</x-table.th>
                <x-table.th>
                    <x-icons.order
                        :data="[
                            'column' => 'name',
                            'currentColumn' => $filters['column'],
                            'order' => $filters['order'],
                            'route' => 'providers.index'
                        ]"
                    >Nombre</x-icons.order>
                </x-table.th>
                <x-table.th>
                    <div class="hidden sm:block text-center">
                        Inspeccionar
                    </div>
                </x-table.th>
            </x-table.tr>
        </x-slot:head>
        <x-slot:body>
            @foreach($providers as $provider)
                <x-table.tr>
                    <x-table.td>
                        {{$provider->n}}
                    </x-table.td>
                    <x-table.td>
                        {{$provider->name}}
                    </x-table.td>
                    <x-table.td>
                        <div class="text-center">
                            <a href="{{route('providers.show', $provider->id)}}" class="inline-block w-5 h-5">
                                <x-icons.magnifying-glass
                                    class="w-full h-full"
                                />
                            </a>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
    {{$providers->links()}}
    @if($providers->isEmpty())
        <p class="text-red-500">
            No se encontraron proveedores...
        </p>
    @endif

</x-layouts.primary>
