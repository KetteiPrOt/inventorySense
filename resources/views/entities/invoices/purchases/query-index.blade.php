<x-layouts.primary
    header="Consultar reporte de compras"
>
    <form action="{{route('purchases.index')}}">
        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Detalles de consulta
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Especifique el intervalo de consulta y seleccione el tipo de reporte de compras que desea consultar.
                </p>
            </header>

            <div class="sm:flex flex-wrap">
                <div class="mb-6 sm:mb-0 sm:mr-6">
                    @php
                        $lastMonth = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
                    @endphp
                    <x-input-label for="dateFromInput" :required="true">
                        Desde
                    </x-input-label>
                    <x-date-input
                        required
                        id="dateFromInput"
                        name="date_from"
                        value="{{old('date_from', date('Y-m-d', $lastMonth))}}"
                        max="{{date('Y-m-d')}}"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('date_from')" />
                </div>

                <div>
                    <x-input-label for="dateToInput" :required="true">
                        Hasta
                    </x-input-label>
                    <x-date-input
                        required
                        id="dateToInput"
                        name="date_to"
                        value="{{old('date_to', date('Y-m-d'))}}"
                        max="{{date('Y-m-d')}}"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('date_to')" />
                </div>
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
                                    class="rounded-full mr-2"
                                    name="report_type"
                                />
                                <label for="reportTypeInput0">
                                    Todas las compras
                                </label>
                                </x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.td>
                                <input
                                    type="radio"
                                    value="only-paid"
                                    required
                                    @checked(old('report_type' == 'only-paid'))
                                    id="reportTypeInput1"
                                    class="rounded-full mr-2"
                                    name="report_type"
                                />
                                <label for="reportTypeInput1">
                                    Solo compras <strong>pagadas</strong>
                                </label>
                                </x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.td>
                                <input
                                    type="radio"
                                    value="only-not-paid"
                                    required
                                    @checked(old('report_type' == 'only-not-paid'))
                                    id="reportTypeInput2"
                                    class="rounded-full mr-2"
                                    name="report_type"
                                />
                                <label for="reportTypeInput2">
                                    Solo compras <strong>no</strong> pagadas
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
    </form>
</x-layouts.primary>