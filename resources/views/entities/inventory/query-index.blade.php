<x-layouts.primary
    header="Consultar inventario"
>
    <form action="{{route('inventory.index')}}">
        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Detalles de consulta
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                    Opcionalmemnte especifique una bodega donde consultar el inventario.
                </p>
            </header>

            <div>
                <livewire:entities.inventory.query-index.choose-warehouse
                    :required="false"
                />
                <x-input-error :messages="$errors->get('search_product')" />
            </div>

            <div>
                <x-input-label for="reportTypeInput0" :required="true">
                    Tipo de reporte
                </x-input-label>
                <x-table class="max-w-sm">
                    <x-slot:body>
                        <x-table.tr>
                            <x-table.td>
                                <input
                                    type="radio"
                                    value="all"
                                    required
                                    @checked(old('report_type' == 'all', true))
                                    id="reportTypeInput0"
                                    class="report-type-input rounded-full mr-2"
                                    name="report_type"
                                />
                                <label for="reportTypeInput0">
                                    Todos los produtos
                                </label>
                                </x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.td>
                                <input
                                    type="radio"
                                    value="under_min_stock"
                                    required
                                    @checked(old('report_type' == 'under_min_stock'))
                                    id="reportTypeInput1"
                                    class="report-type-input rounded-full mr-2"
                                    name="report_type"
                                />
                                <label for="reportTypeInput1">
                                    Debajo de stock mínimo
                                </label>
                                </x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.td>
                                <input
                                    type="radio"
                                    value="not_stock"
                                    required
                                    @checked(old('report_type' == 'not_stock'))
                                    id="reportTypeInput2"
                                    class="report-type-input rounded-full mr-2"
                                    name="report_type"
                                />
                                <label for="reportTypeInput2">
                                    Sin stock
                                </label>
                                </x-table.td>
                        </x-table.tr>
                    </x-slot:body>
                </x-table>
                <x-input-error class="mt-2" :messages="$errors->get('report_type')" />
            </div>
        </section>

        <div class="flex justify-center sm:justify-start mt-6">
            <x-primary-button>
                Consultar
            </x-primary-button>
        </div>

        <x-input-error :messages="$errors->get('page')" />
        <x-input-error :messages="$errors->get('order')" />
        <x-input-error :messages="$errors->get('column')" />
    </form>

    <script>
        const searchWarehouseInput = document.getElementById('warehouseSearch');
        searchWarehouseInput.addEventListener('keyup', () => {
            setTimeout(() => {
                let warehouseInputs = Array.from(
                    document.querySelectorAll('.warehouse-input')
                );
                let checked = false;
                for(let warehouseInput of warehouseInputs){
                    if(warehouseInput.checked){
                        checked = true;
                        break;
                    }
                }
                if(!checked){
                    let reportTypeInputs = Array.from(
                        document.querySelectorAll('.report-type-input')
                    );
                    for(let i = 0; i < reportTypeInputs.length; i++){
                        reportTypeInputs[i].disabled = false;
                    }
                }
            }, 1000);
        });
    </script>
</x-layouts.primary>
