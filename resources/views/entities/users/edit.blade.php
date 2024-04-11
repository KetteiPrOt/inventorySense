<x-layouts.multi-sections.primary
    header="Editar usuario"
    :total-sections="3"
>
    <x-slot:section_1>
            <form action="{{route('users.update', $user->id)}}" method="post">
                @method('put')
                @csrf

                <section class="space-y-6">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            Información del usuario
                        </h2>
                
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Especifique su propia contraseña actual junto al nuevo nombre, correo electrónico, contraseña, dirección, y número de cédula del usuario para actualizarlo.
                        </p>
                    </header>

                    <div>
                        <x-input-label for="current_password" :required="true">
                            Contraseña actual (del administrador)
                        </x-input-label>
                        <x-text-input
                            required
                            id="current_password" name="current_password" type="password"
                            class="mt-1 block w-full max-w-sm"
                            minlength="8" maxlength="255"
                            value="{{old('current_password')}}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('current_password')" />
                    </div>

                    <div>
                        <x-input-label for="name" :required="true">
                            Nombre del usuario
                        </x-input-label>
                        <x-text-input
                            required
                            id="name" name="name" 
                            class="mt-1 block w-full max-w-sm"
                            minlength="2" maxlength="255"
                            value="{{old('name', $user->name)}}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :required="true">
                            Correo electrónico del usuario
                        </x-input-label>
                        <x-text-input
                            required
                            id="email" name="email" type="email"
                            class="mt-1 block w-full max-w-sm"
                            minlength="10" maxlength="255"
                            value="{{old('email', $user->email)}}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="password">
                            Nueva contraseña (del usuario)
                        </x-input-label>
                        <x-text-input
                            id="password" name="password" type="password"
                            class="mt-1 block w-full max-w-sm"
                            minlength="8" maxlength="255"
                            value="{{old('password')}}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation">
                            Confirmar nueva contraseña (del usuario)
                        </x-input-label>
                        <x-text-input
                            id="password_confirmation" name="password_confirmation" type="password"
                            class="mt-1 block w-full max-w-sm"
                            minlength="8" maxlength="255"
                            value="{{old('password_confirmation')}}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                    </div>

                    <div>
                        <x-input-label for="address">
                            Dirección del usuario
                        </x-input-label>
                        <x-text-input
                            id="address" name="address"
                            class="mt-1 block w-full max-w-sm"
                            minlength="2" maxlength="255"
                            value="{{old('address', $user->address)}}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>

                    <div>
                        <x-input-label for="identity_card">
                            Número de cédula del usuario
                        </x-input-label>
                        <x-text-input
                            id="identity_card" name="identity_card"
                            class="mt-1 block w-full max-w-sm"
                            minlength="10" maxlength="20"
                            value="{{old('identity_card', $user->identity_card)}}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('identity_card')" />
                    </div>
                </section>

                <div class="flex justify-center sm:justify-start mt-6">
                    <x-primary-button class="mr-1">
                        Guardar
                    </x-primary-button>

                    <x-secondary-link-button :href="route('users.index')" class="ml-1">
                        Cancelar
                    </x-secondary-link-button>
                </div>
            </form>
    </x-slot:section_1>

    <x-slot:section_2>
        <livewire:entities.users.edit.roles
            :$user
        />
    </x-slot:section_2>

    <x-slot:section_3>
        <form action="{{route('users.update-permissions', $user->id)}}" method="post">
            @method('put')
            @csrf

            <section class="space-y-6">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        Permisos del usuario
                    </h2>
            
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Asigne o revoque directamente permisos para el usuario. Los permisos directos de la aplicación son independientes de los roles que pueda tener el usuario.
                    </p>
                </header>

                <div>
                    <x-entities.permissions.edit.input
                        :permissions="$user->getDirectPermissions()"
                        :$translator
                    />
                </div>
            </section>

            <div class="flex justify-center sm:justify-start mt-6">
                <x-primary-button class="mr-1">
                    Guardar
                </x-primary-button>

                <x-secondary-link-button :href="route('users.index')" class="ml-1">
                    Cancelar
                </x-secondary-link-button>
            </div>
        </form>
    </x-slot:section_3>
</x-layouts.multi-sections.primary>
