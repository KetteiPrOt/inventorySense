<x-layouts.primary
    header="Crear producto"
>
    <form action="{{route('products.store')}}" method="post">
        @csrf

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Información del producto
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Seleccione el tipo, la presentación, y especifique el nombre del producto para crearlo.
                </p>
            </header>

        
            <div>
                <livewire:entities.products.types.index.choose />

                <a href="#" class="text-blue-400 underline inline-block mt-2">
                    Administrar tipos
                </a>
            </div>

            <div>
                <x-input-label for="name" :required="true">
                    Nombre
                </x-input-label>
                <x-text-input
                    required
                    id="name" name="product_name" 
                    class="mt-1 block w-full max-w-sm"
                    minlength="2" maxlength="255"
                />
                <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
            </div>

            <div>
                <livewire:entities.products.presentations.index.choose />

                <a href="#" class="text-blue-400 underline inline-block mt-2">
                    Administrar presentaciones
                </a>
            </div>
        </section>

        <section class="space-y-6 mt-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Precios de venta
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Especifique el precio de venta del producto, y los precios de oferta a partir de cierto número de unidades.
                </p>
            </header>

            <div>
                <livewire:entities.products.create.sale-prices-input />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button id="submitButton">
                Guardar Producto
            </x-primary-button>
        </div>
    </form>
</x-layouts.primary>
