<div>
    @php
        $visualLayout = false;
    @endphp

    <style>
        .permission-btn-enabled {
            background-color: white;
            opacity: 1;
        }

        .permission-btn-disabled {
            background-color: #F2F3F5; /*bg-gray-200*/
            opacity: 0.5;
        }
    </style>

    <div>
        <div class="{{$visualLayout ? 'bg-green-300' : ''}} flex justify-start">
            
            <div class="
                {{$visualLayout ? 'bg-red-400' : ''}}
                flex justify-center items-center
            ">
                <div class="
                    {{$visualLayout ? 'bg-yellow-200' : ''}}
                    {{-- Main Line Height --}}
                    h-[91.8%]
                    flex justify-center items-center
                    border-r-2 border-black
                ">
                    <span
                        class="p-2 border-2 border-black rounded-full"
                    >Panel</span>
                    <div
                        class="w-5 h-0.5 bg-black"
                    ></div>
                </div>
            </div>
            <div class="{{$visualLayout ? 'bg-blue-400' : ''}}">
                {{-- Permission Buttons --}}
                @foreach($translator->directPermissions as $name)
                    <x-entities.permissions.edit.input.button
                        id="{{$name}}PermissionButton"
                        class="{{!$loop->first ? 'mt-2' : ''}}"
                        :status="
                            $permissions->pluck('name')->contains($name)
                            || !is_null(old($name))
                        "
                    >
                        {{$translator->translate($name)}}
                    </x-entities.permissions.edit.input.button>
                @endforeach
            </div>
    
            {{-- True HTML Inputs --}}
            @foreach($translator->directPermissions as $name)
                <input
                    class="hidden" type="checkbox"
                    name="{{$name}}" id="{{$name}}PermissionInput"
                    @checked(old($name, $permissions->pluck('name')->contains($name)))
                />
            @endforeach    
        </div>
    
        @foreach($translator->directPermissions as $name)
            <x-input-error :messages="$errors->get($name)"/>
        @endforeach
    </div>

    <script>
        const toggleButtonStyle = (input, button) => {
            if(input.checked){
                button.classList.replace(
                    'permission-btn-enabled',
                    'permission-btn-disabled'
                );
            } else {
                button.classList.replace(
                    'permission-btn-disabled',
                    'permission-btn-enabled'
                );
            }
        }
        
        const permissions = {!! json_encode($translator->directPermissions) !!};

        for(let permission of permissions){
            const input = document.getElementById(`${permission}PermissionInput`),
                  button = document.getElementById(`${permission}PermissionButton`);

            button.addEventListener('click', (event) => {
                event.preventDefault();
                toggleButtonStyle(input, button);
                input.checked = !input.checked
            });
        }
    </script>
</div>
