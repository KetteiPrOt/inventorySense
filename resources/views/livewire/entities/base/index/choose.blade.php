<div>
    <x-input-label for="{{$inputName ?? $name}}Search" :required="$required">
        {{ $inputLabel ?? ucfirst($spanishName)}}
    </x-input-label>
    <x-text-input
        id="{{$inputName ?? $name}}Search"
        class="mt-1 block w-full max-w-sm"
        placeholder="Buscar..."
        maxlength="255"
        wire:model.live="search"
    />
    <x-table class="max-w-sm">
        <x-slot:body>
            @if(!is_null($entities))
            @foreach($entities as $entitie)
                <x-table.tr>
                    <x-table.td>
                        <input
                            type="radio"
                            value="{{$entitie->id}}"
                            @required($required)
                            id="{{$inputName ?? $name}}Input{{$entitie->id}}"
                            class="rounded-full mr-2"
                            name="{{$inputName ?? $name}}"
                        />
                        <label for="{{$inputName ?? $name}}Input{{$entitie->id}}">
                            {{$entitie->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endforeach
            @endif
            @if($allOption)
                <x-table.tr>
                    <x-table.td>
                        <input
                            type="radio"
                            value=""
                            @checked($entitieSelectedByDefault === 'all')
                            @required($required)
                            id="all{{$inputName ?? ucfirst($name)}}Input"
                            class="rounded-full mr-2"
                            name="{{$inputName ?? $name}}"
                        />
                        <label for="all{{$inputName ?? ucfirst($name)}}Input">
                            {{
                                $gender == 'male'
                                ? 'Todos'
                                : 'Todas'
                            }}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endif
            @if(is_object($entitieSelectedByDefault))
                <x-table.tr>
                    <x-table.td>
                        <input
                            checked
                            type="radio"
                            value="{{$entitieSelectedByDefault->id}}"
                            @required($required)
                            id="{{$inputName ?? $name}}Input{{$entitieSelectedByDefault->id}}"
                            class="rounded-full mr-2"
                            name="{{$inputName ?? $name}}"
                        />
                        <label for="{{$inputName ?? $name}}Input{{$entitieSelectedByDefault->id}}">
                            {{$entitieSelectedByDefault->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endif
        </x-slot:body>
    </x-table>
    <div class="max-w-sm mt-2">
        {{$entities?->links(data: ['scrollTo' => false])}}
        @if($entities?->isEmpty() && is_null($entitieSelectedByDefault))
            <span class="text-red-500">
                {{
                    $gender == 'male'
                    ? "No se encontró ningún $spanishName"
                    : "No se encontró ninguna $spanishName"
                }}
            </span>
        @endif
    </div>
    <x-input-error class="mt-2" :messages="$errors->get($inputName ?? $name)" />
</div>
