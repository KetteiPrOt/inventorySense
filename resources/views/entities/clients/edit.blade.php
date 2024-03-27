<x-layouts.primary
    header="Editar cliente"
>
    <form action="{{route('clients.update', $client->id)}}" method="post">
        @method('put')
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Información del cliente
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Especifique el nuevo nombre, número telefónico, correo electrónico, RUC, dirección, y número de cédula del cliente para actualizarlo.
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
                    value="{{old('name', $client->name)}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="phone">
                    Número teléfonico
                </x-input-label>
                <x-text-input
                    id="phone" name="phone" 
                    class="mt-1 block w-full max-w-sm"
                    minlength="10" maxlength="20"
                    value="{{old('phone', $client->phone)}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="email">
                    Correo electrónico
                </x-input-label>
                <x-text-input
                    id="email" name="email" type="email"
                    class="mt-1 block w-full max-w-sm"
                    minlength="3" maxlength="400"
                    value="{{old('email', $client->email)}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="ruc">
                    RUC
                </x-input-label>
                <x-text-input
                    id="ruc" name="ruc"
                    class="mt-1 block w-full max-w-sm"
                    minlength="10" maxlength="20"
                    value="{{old('ruc', $client->ruc)}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('ruc')" />
            </div>

            <div>
                <x-input-label for="address">
                    Dirección
                </x-input-label>
                <x-text-input
                    id="address" name="address"
                    class="mt-1 block w-full max-w-sm"
                    minlength="2" maxlength="255"
                    value="{{old('address', $client->address)}}"
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
                    value="{{old('identity_card', $client->identity_card)}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('identity_card')" />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button class="mr-1">
                Guardar
            </x-primary-button>

            <x-secondary-link-button :href="route('clients.show', $client->id)" class="ml-1">
                Cancelar
            </x-secondary-link-button>
        </div>
    </form>
</x-layouts.primary>
