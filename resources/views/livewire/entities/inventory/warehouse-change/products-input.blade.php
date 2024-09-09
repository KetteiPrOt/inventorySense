<div>
    {{-- Label --}}
    <x-input-label
        :required="true"
        :value="'Productos'"
        for="searchProductsInput"
    />

    {{-- Selected --}}
    @if($selectedProducts->isNotEmpty())
        <x-table class="mt-2 mb-4">
            <x-slot:head>
                <x-table.tr :hover="false">
                    <x-table.th>
                        Nombre
                    </x-table.th>
                    <x-table.th class="text-center hidden sm:table-cell">
                        Disponible
                    </x-table.th>
                    <x-table.th class="text-center hidden sm:table-cell">
                        Cantidad
                    </x-table.th>
                    <x-table.th class="hidden sm:table-cell">
                        Quitar
                    </x-table.th>
                </x-table.tr>
            </x-slot>
            <x-slot:body>
                @foreach($selectedProducts as $product)
                    <x-table.tr class="grid grid-cols-3 grid-rows-2 sm:table-row">
                        <x-table.td class="min-w-32 col-span-3">
                            <span class="font-bold sm:font-normal">
                                {{$product->tag}}
                            </span>
                            <input hidden name="products[]" value="{{$product->id}}" />
                            <span class="block sm:hidden">
                                Disponible: {{$product->warehouse_existences}}
                            </span>
                        </x-table.td>
                        <x-table.td class="hidden sm:table-cell text-center">
                            {{$product->warehouse_existences}}
                        </x-table.td>
                        <x-table.td class="col-span-2">
                            <div class="w-full flex justify-center">
                                <x-number-input
                                    name="amounts[]"
                                    min="1"
                                    max="{{$product->warehouse_existences}}"
                                    class="w-full sm:w-auto sm:placeholder:invisible"
                                    placeholder="Cantidad a mover..."
                                />
                            </div>
                        </x-table.td>
                        <x-table.td class="flex items-center justify-center sm:table-cell">
                            <x-danger-button
                                wire:click.prevent="removeProduct({{$product->id}})"
                            >
                                X
                            </x-danger-button>
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            </x-slot>
        </x-table>

        {{-- Label --}}
        <x-input-label
            :required="false"
            :value="'Busqueda'"
            for="searchProductsInput"
        />
    @endif

    {{-- Search --}}
    <x-text-input
        wire:model.live.debounce.300ms="search"
        placeholder="Buscar..."
        id="searchProductsInput"
    />
    @error('search')
        <x-input-simple-error>{{$message}}</x-input-simple-error>
    @enderror
    @error('products')
        <x-input-simple-error>{{$message}}</x-input-simple-error>
    @enderror
    @error('amounts')
        <x-input-simple-error>{{$message}}</x-input-simple-error>
    @enderror
    @error('products.*')
        <x-input-simple-error>{{$message}}</x-input-simple-error>
    @enderror
    @error('amounts.*')
        <x-input-simple-error>{{$message}}</x-input-simple-error>
    @enderror

    {{-- Results --}}
    <div class="overflow-x-auto">
        <x-table class="mt-4 mb-2">
            <x-slot:head>
                <x-table.tr :hover="false">
                    <x-table.th>
                        Nombre
                    </x-table.th>
                    <x-table.th class="text-center">
                        Disponible
                    </x-table.th>
                    <x-table.th>
                        Tomar
                    </x-table.th>
                </x-table.tr>
            </x-slot>
            <x-slot:body>
                @forelse($products as $product)
                    <x-table.tr>
                        <x-table.td class="min-w-28">
                            {{$product->tag}}
                        </x-table.td>
                        <x-table.td class="text-center">
                            {{$product->warehouse_existences}}
                        </x-table.td>
                        <x-table.td>
                            @if($product->warehouse_existences > 0)
                                <x-secondary-button
                                    wire:click.prevent="selectProduct({{$product->id}})"
                                >
                                    <x-icons.take-hand class="w-5 h-5" />
                                </x-secondary-button>
                            @else
                                <x-secondary-button :disabled="true">
                                    <x-icons.take-hand class="w-5 h-5" />
                                </x-secondary-button>
                            @endif
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td class="min-w-32">
                            Sin resultados para la búsqueda...
                        </x-table.td>
                        <x-table.td></x-table.td>
                        <x-table.td></x-table.td>
                    </x-table.tr>
                @endforelse
            </x-slot>
        </x-table>
    </div>
    {{$products->links(data: ['scrollTo' => false])}}
</div>
