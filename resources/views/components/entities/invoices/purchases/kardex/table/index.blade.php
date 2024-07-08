<div class="max-w-7xl overflow-x-auto">
<table
    {!! $attributes->merge(['class' => "
        mt-2 border-collapse table-auto w-full text-sm
    "]) !!}
>
    <thead>
        <tr class="hidden lg:table-row">
            <th class="p-1 border-b font-medium text-slate-400 text-left"></th>
            <th class="p-1 border-b font-medium text-slate-400 text-left"></th>
            <th class="p-1 border-b font-medium text-slate-400 text-left"></th>
            <th class="p-1 border-b font-medium text-slate-400 text-left"></th>
            <th class="p-1 border border-r-0 font-medium text-slate-400 text-left"></th>
            <th class="p-1 border-b border-t font-medium text-slate-400 text-center">
                Ingresos
            </th>
            <th class="p-1 border border-l-0 font-medium text-slate-400 text-left"></th>
            <th class="p-1 border border-r-0 font-medium text-slate-400 text-left"></th>
            <th class="p-1 border-b border-t font-medium text-slate-400 text-center">
                Egresos
            </th>
            <th class="p-1 border border-l-0 font-medium text-slate-400 text-left"></th>
            <th class="p-1 border border-r-0 font-medium text-slate-400 text-left"></th>
            <th class="p-1 border-b border-t font-medium text-slate-400 text-center">
                Existencias
            </th>
            <th class="p-1 border border-l-0 font-medium text-slate-400 text-left"></th>
        </tr>
        <tr class="hidden lg:table-row">
            <th class="p-1 border font-medium text-slate-400 text-left">
                Fecha
            </th>
            <th class="p-1 border font-medium text-slate-400 text-left">
                Proveedor/Vendedor
            </th>
            <th class="p-1 border font-medium text-slate-400 text-left">
                Factura
            </th>
            <th class="p-1 border-b border-r font-medium text-slate-400 text-left">
                Tipo de movimiento
            </th>
            <th class="p-1 border-b font-medium text-slate-400 text-center">
                CANT
            </th>
            <th class="p-1 border-b font-medium text-slate-400 text-center">
                P.UNIT
            </th>
            <th class="p-1 border-b border-r font-medium text-slate-400 text-center">
                P.TOTAL
            </th>
            <th class="p-1 border-b font-medium text-slate-400 text-center">
                CANT
            </th>
            <th class="p-1 border-b font-medium text-slate-400 text-center">
                P.UNIT
            </th>
            <th class="p-1 border-b border-r font-medium text-slate-400 text-center">
                P.TOTAL
            </th>
            <th class="p-1 border-b font-medium text-slate-400 text-center">
                CANT
            </th>
            <th class="p-1 border-b font-medium text-slate-400 text-center">
                P.UNIT
            </th>
            <th class="p-1 border-b border-r font-medium text-slate-400 text-center">
                P.TOTAL
            </th>
        </tr>
    </thead>

    <tbody class="bg-white">
        {{ $rows }}
    </tbody>
</table>
</div>
