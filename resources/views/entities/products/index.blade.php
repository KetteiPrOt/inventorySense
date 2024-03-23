<x-layouts.primary
    header="Productos"
>

    <div class="flex flex-col items-center sm:items-start">
        <x-secondary-link-button
            :href="route('products.create')"
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
                <x-table.th>
                    N.
                </x-table.th>
                <x-table.th>
                    <x-icons.order
                        :data="[
                            'column' => 'tag',
                            'currentColumn' => $filters['column'],
                            'order' => $filters['order'],
                            'route' => 'products.index'
                        ]"
                    >Nombre</x-icons.order>
                </x-table.th>
                <x-table.th
                    class="hidden sm:table-cell text-center"
                >
                    Inventario Iniciado
                </x-table.th>
            </x-table.tr>
        </x-slot:head>
        <x-slot:body>
            @foreach($products as $product)
                <x-table.tr
                    :danger="!$product->started_inventory"
                >
                    <x-table.td>
                        {{$product->n}}
                    </x-table.td>
                    <x-table.td>
                        <a href="{{route('products.show', $product->id)}}">
                            {{$product->tag}}
                        </a>
                    </x-table.td>
                    <x-table.td
                        class="hidden sm:table-cell text-center"
                    >
                        {{$product->started_inventory
                            ? 'Si' : 'No'}}
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
    {{$products->links()}}
    @if($products->isEmpty())
        <p class="text-red-500">
            No se encontraron productos...
        </p>
    @endif

</x-layouts.primary>
