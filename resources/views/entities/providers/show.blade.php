<x-layouts.primary
    header="Ver proveedor"
>
    <div class="sm:flex mb-6">
        <div class="mb-4 sm:mb-0 sm:mr-12">
            <p>
                <strong>Proveedor</strong> <br>
                {{$provider->name}}
            </p>

            @if($provider->phone)
            <p>
                <strong>Número teléfonico</strong> <br>
                {{$provider->phone}}
            </p>
            @endif

            @if($provider->email)
            <p>
                <strong>Correo electrónico</strong> <br>
                {{$provider->email}}
            </p>
            @endif
        </div>
        <div>
            <p>
                <strong>Creado el</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($provider->created_at));
                }}
            </p>
            <p>
                <strong>Ultima actualización</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($provider->updated_at));
                }}
            </p>
        </div>
    </div>

    @if(
        $provider->ruc
        || $provider->address
        || $provider->social_reason
    )
    <div class="mb-6 sm:mr-12">
        @if($provider->ruc)
        <p>
            <strong>RUC</strong> <br>
            {{$provider->ruc}}
        </p>
        @endif

        @if($provider->address)
        <p>
            <strong>Dirección</strong> <br>
            {{$provider->address}}
        </p>
        @endif

        @if($provider->social_reason)
        <p>
            <strong>Razón social</strong> <br>
            {{$provider->social_reason}}
        </p>
        @endif
    </div>
    @endif

    <div>
        <x-secondary-link-button
            :href="route('providers.edit', $provider->id)"
        >
            Editar
        </x-secondary-link-button>
        <x-danger-button
            x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion')"
        >Eliminar</x-danger-button>

        <x-modal name="confirm-product-deletion" :show="$errors->isNotEmpty()" focusable>
            <form
                action="{{route('providers.destroy', $provider->id)}}"
                method="post"
                class="p-6"
            >
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que deseas eliminar el proveedor?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez que el proveedor sea eliminado se perderá <strong>por siempre y de forma irreversible</strong> toda su información. Además en los registros ascociados de compras, ventas, reportes, e inventario se establecerá el proveedor como "Desconocido".
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
