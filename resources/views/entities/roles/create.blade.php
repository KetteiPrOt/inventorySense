<x-layouts.primary
    header="Crear rol"
>
    <form action="{{route('roles.store')}}" method="post">
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Información del rol
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Especifique el nombre y los permisos asociados para crearlo.
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
                    minlength="2" maxlength="125"
                    value="{{old('name')}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label>
                    Permisos
                </x-input-label>
                <x-entities.permissions.edit.input :permissions="null" :$translator />
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