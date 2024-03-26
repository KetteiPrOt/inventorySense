<x-layouts.primary
    header="Editar producto"
>
    <form action="{{route('products.update', $product->id)}}" method="post">
        @csrf
        @method('put')

        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Información del producto
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Seleccione el nuevo tipo, presentación, y especifique el nuevo nombre del producto junto a su stock mínimo para actualizarlo.
                </p>
            </header>

        
            <div>
                <livewire:entities.products.types.index.choose
                    :selected-by-default="$product->type?->id"
                />
            </div>

            <div>
                <x-input-label for="name" :required="true">
                    Nombre
                </x-input-label>
                <x-text-input
                    required
                    value="{{$product->name}}"
                    id="name" name="product_name" 
                    class="mt-1 block w-full max-w-sm"
                    minlength="2" maxlength="255"
                />
                <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
            </div>

            <div>
                <livewire:entities.products.presentations.index.choose
                    :selected-by-default="$product->presentation?->id"
                />
            </div>

            <div>
                <x-input-label for="min_stock" :required="true">
                    Stock Mínimo
                </x-input-label>
                <x-number-input
                    required
                    id="min_stock" name="min_stock" 
                    class="mt-1 block w-full max-w-sm"
                    min="1" max="65000"
                    value="{{$product->min_stock}}"
                />
                <x-input-error class="mt-2" :messages="$errors->get('min_stock')" />
            </div>
        </section>

        <section class="space-y-6 mt-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Precios de venta
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Especifique los nuevos precios de venta del producto, y los precios de oferta a partir de cierto número de unidades.
                </p>
            </header>

            <div>
                <livewire:entities.products.edit.sale-prices-input
                    :sale-prices="$product->salePrices"
                />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button id="submitButton">
                Guardar Producto
            </x-primary-button>
        </div>
    </form>
</x-layouts.primary>