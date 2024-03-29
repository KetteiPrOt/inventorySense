<div>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Roles del usuario
            </h2>
    
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Asigne o remueva roles para el usuario. El usuario <strong>recibirá o perderá los permisos</strong> de la aplicación asociados a ese rol.
            </p>
        </header>

        <div>
            <x-table>
                <x-slot:head>
                    <x-table.tr :hover="false">
                        <x-table.th class="text-center">
                            N.
                        </x-table.th>
                        <x-table.th>
                            Nombre
                        </x-table.th>
                        <x-table.th class="text-center">
                            Quitar
                        </x-table.th>
                    </x-table.tr>
                </x-slot:head>
                <x-slot:body>
                    @foreach($user->roles as $key => $role)
                        <x-table.tr>
                            <x-table.td class="text-center">
                                {{$key + 1}}
                            </x-table.td>
                            <x-table.td>
                                {{$role->name}}
                            </x-table.td>
                            <x-table.td class="text-center">
                                @if($role->name !== $superAdmin)
                                    <x-secondary-button
                                        class="bg-red-500 hover:bg-red-400"
                                        x-data
                                        x-on:click.prevent="$dispatch('open-modal', 'confirm-role{{$role->id}}-deletion')"
                                    >
                                        <x-icons.plus color="#fff" class="w-3 h-3 rotate-45" />
                                    </x-secondary-button>
                                    <x-modal name="confirm-role{{$role->id}}-deletion" :show="$errors->isNotEmpty()" focusable>
                                        <div class="p-6">                    
                                            <h2 class="text-left text-lg font-medium text-gray-900 dark:text-gray-100">
                                                ¿Seguro que deseas remover el rol?
                                            </h2>
                            
                                            <p class="mt-1 text-left text-sm text-gray-600 dark:text-gray-400">
                                                Despues de la confirmación el usuario perderá los permisos de la aplicación asociados mediante este rol.
                                            </p>
                            
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    Cancelar
                                                </x-secondary-button>
                            
                                                <x-danger-button
                                                    x-on:click="
                                                        $dispatch('close')
                                                        setTimeout(() => $wire.removeRole({{$role->id}}, 500));
                                                    "
                                                    class="ms-3"
                                                >
                                                    Quitar
                                                </x-danger-button>
                                            </div>
                                        </div>
                                    </x-modal>
                                @else
                                    <x-secondary-button
                                        class="bg-red-100 hover:bg-red-100"
                                    >
                                        <x-icons.plus color="#fff" class="w-3 h-3 rotate-45" />
                                    </x-secondary-button>
                                @endif
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-slot:body>
            </x-table>
            @if($user->roles->isEmpty())
                <span class="text-red-500">
                    No se encontraron roles asignados...
                </span>
            @endif
        </div>

        <div>
            <x-input-label for="searchRole">
                Agregar roles
            </x-input-label>
            <x-text-input
                required
                id="searchRole"
                class="mt-1 block w-full max-w-sm"
                minlength="2" maxlength="255"
                placeholder="Buscar..."
                wire:model.live="search"
            />
            <x-table class="mt-1 mb-1">
                <x-slot:head>
                    <x-table.tr :hover="false">
                        <x-table.th class="text-center">
                            N.
                        </x-table.th>
                        <x-table.th>
                            Nombre
                        </x-table.th>
                        <x-table.th class="text-center">
                            Agregar
                        </x-table.th>
                    </x-table.tr>
                </x-slot:head>
                <x-slot:body>
                    @foreach($roles as $role)
                        <x-table.tr>
                            <x-table.td class="text-center">
                                {{$role->n}}
                            </x-table.td>
                            <x-table.td>
                                {{$role->name}}
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-secondary-button
                                    class="bg-green-500 hover:bg-green-400"
                                    wire:click="assignRole({{$role->id}})"
                                >
                                    <x-icons.plus color="#fff" class="w-3 h-3" />
                                </x-secondary-button>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-slot:body>
            </x-table>
            {{$roles->links(data: ['scrollTo' => false])}}
            @if($roles->isEmpty())
                <span class="text-red-500">
                    No se encontraron roles...
                </span>
            @endif
        </div>
    </section>
</div>
