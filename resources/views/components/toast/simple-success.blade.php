@props(['show' => false])
<div>
    <div
        x-data="{
            open: {{$show ? 'true' : 'false'}}
        }"
        x-show="open" x-transition
        x-init="setTimeout(() => {open = false}, 10000)"
        id="status-toast" class="pointer-events-auto z-[1050] mx-auto w-96 max-w-full rounded-lg bg-green-100 bg-clip-padding text-sm text-primary-700 shadow-lg dark:bg-slate-900 dark:text-primary-500 fixed animate-[fade-out_0.3s_both] p-[auto] motion-reduce:transition-none motion-reduce:animate-none" role="alert" aria-live="assertive" aria-atomic="true" style="top: 10px; right: 10px;"
    >
        <div class="flex items-center justify-between rounded-t-lg border-b-2 border-[#b1c6ea] bg-clip-padding px-4 py-[0.65rem]  dark:border-[#234479]">
            <div class="flex items-center">
            <span class="me-2 py-1 [&amp;>svg]:h-6 [&amp;>svg]:w-6">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"></path>
                </svg>
            </span>
            <p class="font-bold">Aviso</p>
            </div>
            <div class="flex items-center">
            <p class="text-xs">{{date('H:i')}}</p>
            <button
                x-on:click="open = false"
                type="button" class="-me-[0.375rem] ms-3 box-content rounded-none border-none p-[0.25em] text-black opacity-50 hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none dark:brightness-200 dark:grayscale dark:invert" aria-label="Close"
            >
                <span>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>
                </span>
            </button>
            </div>
        </div>
        <div class="break-words rounded-b-lg p-4 text-center">
            Los productos fueron movidos exitosamente.
        </div>
    </div>
</div>