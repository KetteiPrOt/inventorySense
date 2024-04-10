<div>
    <x-input-label for="userSearch" :required="$required">
        Usuario
    </x-input-label>
    <x-text-input
        id="userSearch"
        class="mt-1 block w-full max-w-sm"
        placeholder="Buscar..."
        maxlength="255"
        wire:model.live="search"
    />
    <x-table class="max-w-sm">
        <x-slot:body>
            @if(!is_null($users))
            @foreach($users as $user)
                <x-table.tr>
                    <x-table.td>
                        <input
                            type="radio"
                            value="{{$user->id}}"
                            @required($required)
                            id="userInput{{$user->id}}"
                            class="rounded-full mr-2"
                            name="user"
                        />
                        <label for="userInput{{$user->id}}">
                            {{$user->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endforeach
            @endif
            @if(!is_null($userSelectedByDefault))
                <x-table.tr>
                    <x-table.td>
                        <input
                            checked
                            type="radio"
                            value="{{$userSelectedByDefault->id}}"
                            @required($required)
                            id="userInput{{$userSelectedByDefault->id}}"
                            class="rounded-full mr-2"
                            name="user"
                        />
                        <label for="userInput{{$userSelectedByDefault->id}}">
                            {{$userSelectedByDefault->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endif
        </x-slot:body>
    </x-table>
    <div class="max-w-sm mt-2">
        {{$users?->links(data: ['scrollTo' => false])}}
        @if($users?->isEmpty() && is_null($userSelectedByDefault))
            <span class="text-red-500">
                No se encontraron usuarios...
            </span>
        @endif
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('user')" />
</div>
