<x-layouts.primary
    header="Ver usuario"
>
    <div class="sm:flex mb-6">
        <div class="mb-4 sm:mb-0 sm:mr-12">
            <p>
                <strong>Nombre</strong> <br>
                {{$user->name}}
            </p>

            <p>
                <strong>Correo electrónico</strong> <br>
                {{$user->email}}
            </p>
        </div>
        <div>
            <p>
                <strong>Creado el</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($user->created_at));
                }}
            </p>
            <p>
                <strong>Ultima actualización</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($user->updated_at));
                }}
            </p>
        </div>
    </div>

    @if($user->identity_card || $user->address)
    <div class="mb-6 sm:mr-12">
        @if($user->identity_card)
        <p>
            <strong>Número de cédula</strong> <br>
            {{$user->identity_card}}
        </p>
        @endif

        @if($user->address)
        <p>
            <strong>Dirección</strong> <br>
            {{$user->address}}
        </p>
        @endif
    </div>
    @endif

    <div class="mb-6 sm:mr-12">
        <p>
            <strong>Roles</strong> <br>
            @forelse($user->roles as $role)
                @if($loop->last)
                    {{$role->name}}.
                @else
                    {{$role->name}}, 
                @endif
            @empty
                Ninguno.
            @endforelse
        </p>

        <p>
            <strong>Permisos directos</strong> <br>
            @forelse($user->getDirectPermissions() as $permission)
                @if($loop->last)
                    {{$translator->translate($permission->name)}}.
                @else
                    {{$translator->translate($permission->name)}}, 
                @endif
            @empty
                Ninguno.
            @endforelse
        </p>
    </div>

    <div>
        <x-secondary-link-button
            :href="route('users.edit', $user->id)"
        >
            Editar
        </x-secondary-link-button>
        <x-danger-button
            x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion')"
        >Eliminar</x-danger-button>

        <x-modal name="confirm-product-deletion" :show="$errors->isNotEmpty()" focusable>
            <form
                {{-- action="{{route('users.destroy', $user->id)}}" --}}
                method="post"
                class="p-6"
            >
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que deseas eliminar el usuario?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez que el usuario sea eliminado se perderá <strong>por siempre y de forma irreversible</strong> toda su información. Además en los registros ascociados de compras, ventas, reportes, e inventario se establecerá el usuario como "Eliminado".
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