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

    <div class="max-w-7xl overflow-x-auto">
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
            {{-- Col 1 --}}
            <div class="{{$visualLayout ? 'bg-blue-400' : ''}}">
                <x-entities.permissions.edit.input.button
                    button-id="productsPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('products')
                        || !is_null(old('products'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('products')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="providersPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('providers')
                        || !is_null(old('providers'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('providers')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="clientsPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('clients')
                        || !is_null(old('clients'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('clients')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="create-purchasesPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('create-purchases')
                        || !is_null(old('create-purchases'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('create-purchases')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="kardexPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('kardex')
                        || !is_null(old('kardex'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('kardex')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="purchases-reportPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('purchases-report')
                        || !is_null(old('purchases-report'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('purchases-report')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="create-salesPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('create-sales')
                        || !is_null(old('create-sales'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('create-sales')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="cash-closingPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('cash-closing')
                        || !is_null(old('cash-closing'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                    :right-connection="true"
                >
                    {{$translator->translate('cash-closing')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="sales-reportPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('sales-report')
                        || !is_null(old('sales-report'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                    :right-connection="true"
                >
                    {{$translator->translate('sales-report')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="inventoryPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('inventory')
                        || !is_null(old('inventory'))
                    "
                    :left-connection="true"
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('inventory')}}
                </x-entities.permissions.edit.input.button>
            </div>
            {{-- Col 2 --}}
            <div class="{{$visualLayout ? 'bg-blue-400' : ''}}">
                <x-entities.permissions.edit.input.button class="opacity-0">
                    foo_1
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_2
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_3
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_4
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_5
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_6
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_7
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="see-all-incomesPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('see-all-incomes')
                        || !is_null(old('see-all-incomes'))
                    "
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('see-all-incomes')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="see-all-salesPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('see-all-sales')
                        || !is_null(old('see-all-sales'))
                    "
                    :right-connection="true"
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('see-all-sales')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_10
                </x-entities.permissions.edit.input.button>
            </div>
            {{-- Col 3 --}}
            <div class="{{$visualLayout ? 'bg-blue-400' : ''}}">
                <x-entities.permissions.edit.input.button class="opacity-0">
                    foo_1
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_2
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_3
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_4
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_5
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_6
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_7
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_8
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button
                    class="mt-2"
                    button-id="edit-all-salesPermissionButton"
                    :status="
                        $permissions->pluck('name')->contains('edit-all-sales')
                        || !is_null(old('edit-all-sales'))
                    "
                    :visual-layout="$visualLayout"
                >
                    {{$translator->translate('edit-all-sales')}}
                </x-entities.permissions.edit.input.button>
                <x-entities.permissions.edit.input.button class="opacity-0 mt-2">
                    foo_10
                </x-entities.permissions.edit.input.button>
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
        const disableInput = (input, button) => {
            input.checked = false;
            let replaced = button.classList.replace(
                'permission-btn-enabled',
                'permission-btn-disabled'
            );
            if(!replaced){
                button.classList.add('permission-btn-disabled');
            }
        }

        const enableInput = (input, button) => {
            input.checked = true;
            let replaced = button.classList.replace(
                'permission-btn-disabled',
                'permission-btn-enabled'
            );
            if(!replaced){
                button.classList.add('permission-btn-enabled');
            }
        }

        const toggleInput = (input, button) => {
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
            input.checked = !input.checked;
        }

        const independentInputs = [
            'products',
            'providers',
            'clients',
            'create-purchases',
            'kardex',
            'purchases-report',
            'create-sales',
            'inventory'
        ];

        for(let independentInput of independentInputs){
            let input = document.getElementById(`${independentInput}PermissionInput`),
                button = document.getElementById(`${independentInput}PermissionButton`);

            button.addEventListener('click', (event) => {
                event.preventDefault();
                toggleInput(input, button);
            });
        }

        // cash-closing [Permission]
        let cashClosingInput = document.getElementById(`cash-closingPermissionInput`),
            cashClosingButton = document.getElementById(`cash-closingPermissionButton`),
            seeAllIncomesInput = document.getElementById(`see-all-incomesPermissionInput`),
            seeAllIncomesButton = document.getElementById(`see-all-incomesPermissionButton`);

        cashClosingButton.addEventListener('click', (event) => {
            event.preventDefault();
            toggleInput(cashClosingInput, cashClosingButton);
            if(!cashClosingInput.checked){
                disableInput(seeAllIncomesInput, seeAllIncomesButton);
            }
        });

        // see-all-incomes [Permission]
        seeAllIncomesButton.addEventListener('click', (event) => {
            event.preventDefault();
            toggleInput(seeAllIncomesInput, seeAllIncomesButton);
            if(seeAllIncomesInput.checked){
                enableInput(cashClosingInput, cashClosingButton);
            }
        });

        // sales-report [Permission]
        let salesReportInput = document.getElementById(`sales-reportPermissionInput`),
            salesReportButton = document.getElementById(`sales-reportPermissionButton`),
            seeAllSalesInput = document.getElementById(`see-all-salesPermissionInput`),
            seeAllSalesButton = document.getElementById(`see-all-salesPermissionButton`),
            editAllSalesInput = document.getElementById(`edit-all-salesPermissionInput`),
            editAllSalesButton = document.getElementById(`edit-all-salesPermissionButton`);

        salesReportButton.addEventListener('click', (event) => {
            event.preventDefault();
            toggleInput(salesReportInput, salesReportButton);
            // Verify see-all-sales and edit-all-sales permissions
            if(!salesReportInput.checked){
                disableInput(seeAllSalesInput, seeAllSalesButton);
                disableInput(editAllSalesInput, editAllSalesButton);
            }
        });

        // see-all-sales [Permission]
        seeAllSalesButton.addEventListener('click', (event) => {
            event.preventDefault();
            toggleInput(seeAllSalesInput, seeAllSalesButton);
            // Verify sales-report and edit-all-sales permissions
            if(seeAllSalesInput.checked){
                enableInput(salesReportInput, salesReportButton);
            } else {
                disableInput(editAllSalesInput, editAllSalesButton);
            }
        });

        // edit-all-sales [Permission]
        editAllSalesButton.addEventListener('click', (event) => {
            event.preventDefault();
            toggleInput(editAllSalesInput, editAllSalesButton);
            // Verify sales-report and see-all-sales permissions
            if(editAllSalesInput.checked){
                enableInput(salesReportInput, salesReportButton);
                enableInput(seeAllSalesInput, seeAllSalesButton);
            }
        });
    </script>
</div>
