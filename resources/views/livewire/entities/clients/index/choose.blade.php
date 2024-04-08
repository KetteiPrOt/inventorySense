<div>
    <x-input-label for="clientSearch" :required="$required">
        Cliente
    </x-input-label>
    <x-text-input
        id="clientSearch"
        class="mt-1 block w-full max-w-sm"
        placeholder="Buscar..."
        maxlength="255"
        wire:model.live="search"
    />
    <x-table class="max-w-sm">
        <x-slot:body>
            @if(!is_null($clients))
            @foreach($clients as $client)
                <x-table.tr>
                    <x-table.td>
                        <input
                            type="radio"
                            value="{{$client->id}}"
                            @required($required)
                            id="clientInput{{$client->id}}"
                            class="rounded-full mr-2"
                            name="client"
                        />
                        <label for="clientInput{{$client->id}}">
                            {{$client->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endforeach
            @endif
            @if(!is_null($clientSelectedByDefault))
                <x-table.tr>
                    <x-table.td>
                        <input
                            checked
                            type="radio"
                            value="{{$clientSelectedByDefault->id}}"
                            @required($required)
                            id="clientInput{{$clientSelectedByDefault->id}}"
                            class="rounded-full mr-2"
                            name="client"
                        />
                        <label for="clientInput{{$clientSelectedByDefault->id}}">
                            {{$clientSelectedByDefault->content}}ml
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endif
        </x-slot:body>
    </x-table>
    <div class="max-w-sm mt-2">
        {{$clients?->links(data: ['scrollTo' => false])}}
        @if($clients?->isEmpty() && is_null($clientSelectedByDefault))
            <span class="text-red-500">
                No se encontraron clientes...
            </span>
        @endif
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('client')" />
</div>
