<x-layouts.primary
    header="Crear proveedor"
>
    <form action="{{route('providers.store')}}" method="post">
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Información del proveedor
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Especifique el nombre, número telefónico, correo electrónico, RUC, dirección, y razón social del proveedor para crearlo.
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
                />
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

            <div>
                <x-input-label for="social_reason">
                    Razón social
                </x-input-label>
                <x-text-input
                    id="social_reason" name="social_reason"
                    class="mt-1 block w-full max-w-sm"
                    minlength="2" maxlength="255"
                />
                <x-input-error class="mt-2" :messages="$errors->get('social_reason')" />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button class="mr-1">
                Guardar
            </x-primary-button>

            <x-secondary-link-button :href="route('providers.index')" class="ml-1">
                Cancelar
            </x-secondary-link-button>
        </div>
    </form>
</x-layouts.primary>