<x-layouts.primary
    header="Crear usuario"
>
    <form action="{{route('users.store')}}" method="post">
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Información del usuario
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Especifique el nombre, correo electrónico, contraseña, dirección, y número de cédula del usuario para crearlo.
                </p>
            </header>

            <div>
                <x-input-label for="name" :required="true">
                    Nombre
                </x-input-label>
                <x-text-input
                    required
                    id="name" name="name" 
                    class="mt-1 block w-full max-w-sm"
                    minlength="2" maxlength="255"
                    value="{{old('name')}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :required="true">
                    Correo electrónico
                </x-input-label>
                <x-text-input
                    required
                    id="email" name="email" type="email"
                    class="mt-1 block w-full max-w-sm"
                    minlength="10" maxlength="255"
                    value="{{old('email')}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="password" :required="true">
                    Contraseña
                </x-input-label>
                <x-text-input
                    required
                    id="password" name="password" type="password"
                    class="mt-1 block w-full max-w-sm"
                    minlength="8" maxlength="255"
                    value="{{old('password')}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('password')" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :required="true">
                    Confirmar contraseña
                </x-input-label>
                <x-text-input
                    required
                    id="password_confirmation" name="password_confirmation" type="password"
                    class="mt-1 block w-full max-w-sm"
                    minlength="8" maxlength="255"
                    value="{{old('password_confirmation')}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
            </div>

            <div>
                <x-input-label for="address">
                    Dirección
                </x-input-label>
                <x-text-input
                    id="address" name="address"
                    class="mt-1 block w-full max-w-sm"
                    minlength="2" maxlength="255"
                    value="{{old('address')}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

            <div>
                <x-input-label for="identity_card">
                    Número de cédula
                </x-input-label>
                <x-text-input
                    id="identity_card" name="identity_card"
                    class="mt-1 block w-full max-w-sm"
                    minlength="10" maxlength="20"
                    value="{{old('identity_card')}}"
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
</x-layouts.primary>
