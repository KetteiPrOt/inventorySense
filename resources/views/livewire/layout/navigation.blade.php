<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/');
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo
                            class="w-16 h-16"
                        />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 lg:-my-px lg:ms-10 lg:flex">
                    @can('products')
                        <x-nav-dropdown
                            tag="Productos" width="30"
                            :active="request()->routeIs([
                                'products.*',
                                'product-types.index',
                                'product-presentations.index'
                            ])"
                        >
                            <x-dropdown-link
                                :href="route('products.index')"
                                :active="request()->routeIs('products.*')"
                            >
                                Listado
                            </x-dropdown-link>
                            <x-dropdown-link
                                :href="route('product-types.index')"
                                :active="request()->routeIs('product-types.index')"
                            >
                                Tipos
                            </x-dropdown-link>
                            <x-dropdown-link
                                :href="route('product-presentations.index')"
                                :active="request()->routeIs('product-presentations.index')"
                            >
                                Presentaciones
                            </x-dropdown-link>
                        </x-nav-dropdown>
                    @endcan
                    @canany(['providers', 'clients', 'users'])
                        <x-nav-dropdown
                            tag="Personas" width="30"
                            :active="request()->routeIs([
                                'providers.*',
                                'clients.*',
                                'users.*'
                            ])"
                        >
                            @can('providers')
                                <x-dropdown-link
                                    :href="route('providers.index')"
                                    :active="request()->routeIs('providers.*')"
                                >
                                    Proveedores
                                </x-dropdown-link>
                            @endcan
                            @can('clients')
                                <x-dropdown-link
                                    :href="route('clients.index')"
                                    :active="request()->routeIs('clients.*')"
                                >
                                    Clientes
                                </x-dropdown-link>
                            @endcan
                            @can('users')
                                <x-dropdown-link
                                    :href="route('users.index')"
                                    :active="request()->routeIs('users.*')"
                                >
                                    Usuarios
                                </x-dropdown-link>
                            @endcan
                        </x-nav-dropdown>
                    @endcan
                    @canany(['create-purchases', 'kardex', 'purchases-report'])
                        <x-nav-dropdown
                            tag="Compras" width="30"
                            :active="request()->routeIs([
                                'purchases.*'
                            ])"
                        >
                            @can('create-purchases')
                                <x-dropdown-link
                                    :href="route('purchases.create')"
                                    :active="request()->routeIs('purchases.create')"
                                >
                                    Registrar
                                </x-dropdown-link>
                            @endcan
                            @can('kardex')
                                <x-dropdown-link
                                    :href="route('purchases.query-kardex')"
                                    :active="request()->routeIs(['purchases.query-kardex', 'purchases.kardex'])"
                                >
                                    Kardex
                                </x-dropdown-link>
                            @endcan
                            @can('purchases-report')
                                <x-dropdown-link
                                    :href="route('purchases.query-index')"
                                    :active="request()->routeIs(['purchases.query-index', 'purchases.index'])"
                                >
                                    Reporte
                                </x-dropdown-link>
                            @endcan
                        </x-nav-dropdown>
                    @endcan
                    @canany(['create-sales', 'cash-closing', 'sales-report'])
                        <x-nav-dropdown
                            tag="Ventas" width="32"
                            :active="request()->routeIs([
                                'sales.*'
                            ])"
                        >
                            @can('create-sales')
                                <x-dropdown-link
                                    :href="route('sales.create')"
                                    :active="request()->routeIs('sales.create')"
                                >
                                    Registrar
                                </x-dropdown-link>
                            @endcan
                            @can('cash-closing')
                                <x-dropdown-link
                                    :href="route('sales.query-cash-closing')"
                                    :active="request()->routeIs(['sales.query-cash-closing', 'sales.cash-closing'])"
                                >
                                    Cierre de caja
                                </x-dropdown-link>
                            @endcan
                        </x-nav-dropdown>
                    @endcan
                    @can('roles')
                        <x-nav-link
                            href="{{route('roles.index')}}"
                            :active="request()->routeIs('roles.*')"
                        >
                            Roles
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center border-b-2 border-transparent lg:ms-6 has-[:focus]:outline-none has-[:focus]:border-gray-300">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:text-gray-700 transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start focus:outline-none focus:bg-gray-100">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @can('products')
                <x-responsive-nav-dropdown
                    tag="Productos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" width="24"
                    :active="request()->routeIs([
                        'products.*',
                        'product-types.index',
                        'product-presentations.index'
                    ])"
                >
                    <x-dropdown-link
                        :href="route('products.index')"
                        :active="request()->routeIs('products.*')"
                    >
                        Listado
                    </x-dropdown-link>
                    <x-dropdown-link
                        :href="route('product-types.index')"
                        :active="request()->routeIs('product-types.index')"
                    >
                        Tipos
                    </x-dropdown-link>
                    <x-dropdown-link
                        :href="route('product-presentations.index')"
                        :active="request()->routeIs('product-presentations.index')"
                    >
                        Presentaciones
                    </x-dropdown-link>
                </x-responsive-nav-dropdown>
            @endcan
            @canany(['providers', 'clients', 'users'])
                <x-responsive-nav-dropdown
                    tag="Personas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" width="24"
                    :active="request()->routeIs([
                        'providers.*',
                        'clients.*',
                        'users.*'
                    ])"
                >
                    @can('providers')
                        <x-dropdown-link
                            :href="route('providers.index')"
                            :active="request()->routeIs('providers.*')"
                        >
                            Proveedores
                        </x-dropdown-link>
                    @endcan
                    @can('clients')
                        <x-dropdown-link
                            :href="route('clients.index')"
                            :active="request()->routeIs('clients.*')"
                        >
                            Clientes
                        </x-dropdown-link>
                    @endcan
                    @can('users')
                        <x-dropdown-link
                            :href="route('users.index')"
                            :active="request()->routeIs('users.*')"
                        >
                            Usuarios
                        </x-dropdown-link>
                    @endcan
                </x-responsive-nav-dropdown>
            @endcanany
            @canany(['create-purchases', 'kardex', 'purchases-report'])
                <x-responsive-nav-dropdown
                    tag="Compras &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" width="24"
                    :active="request()->routeIs([
                        'purchases.*'
                    ])"
                >
                    @can('create-purchases')
                        <x-dropdown-link
                            :href="route('purchases.create')"
                            :active="request()->routeIs('purchases.create')"
                        >
                            Registrar
                        </x-dropdown-link>
                    @endcan
                    @can('kardex')
                        <x-dropdown-link
                            :href="route('purchases.query-kardex')"
                            :active="request()->routeIs(['purchases.query-kardex', 'purchases.kardex'])"
                        >
                            Kardex
                        </x-dropdown-link>
                    @endcan
                    @can('purchases-report')
                        <x-dropdown-link
                            :href="route('purchases.query-index')"
                            :active="request()->routeIs(['purchases.query-index', 'purchases.index'])"
                        >
                            Reporte
                        </x-dropdown-link>
                    @endcan
                </x-responsive-nav-dropdown>
            @endcanany
            @canany(['create-sales', 'cash-closing', 'sales-report'])
                <x-responsive-nav-dropdown
                    tag="Ventas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                    width="32"
                    :active="request()->routeIs([
                        'sales.*'
                    ])"
                >
                    @can('create-sales')
                        <x-dropdown-link
                            :href="route('sales.create')"
                            :active="request()->routeIs('sales.create')"
                        >
                            Registrar
                        </x-dropdown-link>
                    @endcan
                    @can('cash-closing')
                        <x-dropdown-link
                            :href="route('sales.query-cash-closing')"
                            :active="request()->routeIs(['sales.query-cash-closing', 'sales.cash-closing'])"
                        >
                            Cierre de caja
                        </x-dropdown-link>
                    @endcan
                </x-responsive-nav-dropdown>
            @endcanany
            @can('roles')
                <x-responsive-nav-link
                    href="{{route('roles.index')}}"
                    :active="request()->routeIs('roles.*')"
                >
                    Roles
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start focus:outline-none focus:bg-gray-100">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
