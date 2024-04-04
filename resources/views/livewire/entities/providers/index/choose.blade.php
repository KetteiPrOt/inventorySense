<div>
    <x-input-label for="providerSearch" :required="$required">
        Proveedor
    </x-input-label>
    <x-text-input
        id="providerSearch"
        class="mt-1 block w-full max-w-sm"
        placeholder="Buscar..."
        maxlength="255"
        wire:model.live="search"
    />
    <x-table class="max-w-sm">
        <x-slot:body>
            @if(!is_null($providers))
            @foreach($providers as $provider)
                <x-table.tr>
                    <x-table.td>
                        <input
                            type="radio"
                            value="{{$provider->id}}"
                            @required($required)
                            id="providerInput{{$provider->id}}"
                            class="rounded-full mr-2"
                            name="provider"
                        />
                        <label for="providerInput{{$provider->id}}">
                            {{$provider->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endforeach
            @endif
            @if(!is_null($providerSelectedByDefault))
                <x-table.tr>
                    <x-table.td>
                        <input
                            checked
                            type="radio"
                            value="{{$providerSelectedByDefault->id}}"
                            @required($required)
                            id="providerInput{{$providerSelectedByDefault->id}}"
                            class="rounded-full mr-2"
                            name="provider"
                        />
                        <label for="providerInput{{$providerSelectedByDefault->id}}">
                            {{$providerSelectedByDefault->content}}ml
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endif
        </x-slot:body>
    </x-table>
    <div class="max-w-sm mt-2">
        {{$providers?->links(data: ['scrollTo' => false])}}
        @if($providers?->isEmpty() && is_null($providerSelectedByDefault))
            <span class="text-red-500">
                No se encontraron proveedores...
            </span>
        @endif
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('provider')" />
</div>
