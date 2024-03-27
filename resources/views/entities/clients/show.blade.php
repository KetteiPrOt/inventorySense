<x-layouts.primary
    header="Ver cliente"
>
    <div class="sm:flex mb-6">
        <div class="mb-4 sm:mb-0 sm:mr-12">
            <p>
                <strong>Cliente</strong> <br>
                {{$client->name}}
            </p>

            @if($client->phone)
            <p>
                <strong>Número teléfonico</strong> <br>
                {{$client->phone}}
            </p>
            @endif

            @if($client->email)
            <p>
                <strong>Correo electrónico</strong> <br>
                {{$client->email}}
            </p>
            @endif
        </div>
        <div>
            <p>
                <strong>Creado el</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($client->created_at));
                }}
            </p>
            <p>
                <strong>Ultima actualización</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($client->updated_at));
                }}
            </p>
        </div>
    </div>

    @if(
        $client->ruc
        || $client->address
        || $client->identity_card
    )
    <div class="sm:flex mb-6">
        <div class="mb-4 sm:mb-0 sm:mr-12">
            @if($client->ruc)
            <p>
                <strong>RUC</strong> <br>
                {{$client->ruc}}
            </p>
            @endif

            @if($client->address)
            <p>
                <strong>Dirección</strong> <br>
                {{$client->address}}
            </p>
            @endif

            @if($client->identity_card)
            <p>
                <strong>Número de cédula</strong> <br>
                {{$client->identity_card}}
            </p>
            @endif
        </div>
    </div>
    @endif

    <div>
        <x-secondary-link-button
            :href="route('clients.edit', $client->id)"
        >
            Editar
        </x-secondary-link-button>
        <x-danger-button
            x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion')"
        >Eliminar</x-danger-button>

        <x-modal name="confirm-product-deletion" :show="$errors->isNotEmpty()" focusable>
            <form
                action="{{route('clients.destroy', $client->id)}}"
                method="post"
                class="p-6"
            >
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que deseas eliminar el cliente?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez que el cliente sea eliminado se perderá <strong>por siempre y de forma irreversible</strong> toda su información. Además en los registros ascociados de compras, ventas, reportes, e inventario se establecerá el cliente como "Consumidor Final".
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
</x-layouts.primary>
