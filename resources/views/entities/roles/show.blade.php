<x-layouts.primary
    header="Ver rol"
>
    <div class="sm:flex mb-6">
        <div class="mb-4 sm:mb-0 sm:mr-12">
            <p>
                <strong>Nombre</strong> <br>
                {{$role->name}}
            </p>
        </div>
        <div>
            <p>
                <strong>Creado el</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($role->created_at));
                }}
            </p>
            <p>
                <strong>Ultima actualización</strong> <br>
                {{
                    date('d/m/Y H:i:s', strtotime($role->updated_at));
                }}
            </p>
        </div>
    </div>

    <div class="mb-6 sm:mr-12">
        <p>
            <strong>Usuarios</strong> <br>
            @forelse($role->users as $user)
                @if($loop->last)
                    {{$user->name}}.
                @else
                    {{$user->name}}, 
                @endif
            @empty
                Ninguno.
            @endforelse
        </p>

        <p>
            <strong>Permisos</strong> <br>
            @if($role->name === $superAdmin)
                Todos.
            @else
                @forelse($role->permissions as $permission)
                    @if($loop->last)
                        {{$translator->translate($permission->name)}}.
                    @else
                        {{$translator->translate($permission->name)}}, 
                    @endif
                @empty
                    Ninguno.
                @endforelse
            @endif
        </p>
    </div>

    @if($role->name !== $superAdmin)
        <div>
            <x-secondary-link-button
                :href="route('roles.edit', $role->id)"
            >
                Editar
            </x-secondary-link-button>

            <x-danger-button
                x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-role-deletion')"
            >Eliminar</x-danger-button>

            <x-modal name="confirm-role-deletion" :show="$errors->isNotEmpty()" focusable>
                <form
                    action="{{route('roles.destroy', $role->id)}}"
                    method="post"
                    class="p-6"
                >
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        ¿Seguro que deseas eliminar el rol?
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Una vez que el rol sea eliminado se perderá <strong>por siempre y de forma irreversible</strong> toda su información. Además los usuarios ascociados <strong>perderán inmediatamente</strong> los permisos otorgados por este rol.
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
    @endif
</x-layouts.primary>