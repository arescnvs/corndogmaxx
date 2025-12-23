<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800 flex"
      x-data="{ sidebarOpen: true, collapsed: false }">

    {{-- SIDEBAR KASIR --}}
    <aside 
        x-bind:class="collapsed ? 'w-16' : 'w-64'"
        class="flex flex-col flex-shrink-0 min-h-screen border-e border-zinc-200 bg-zinc-50 
               dark:border-zinc-700 dark:bg-zinc-900 transition-all duration-300 ease-in-out">

        <!-- HEADER -->
        <div class="flex items-center justify-between p-4 border-b border-zinc-200 dark:border-zinc-700">
            <!-- Logo -->
            <div class="flex items-center justify-center flex-1">
                <img src="/images/cmremove.png" alt="Corndog Maxx Logo" 
                     x-show="!collapsed"
                     x-transition.opacity
                     class="h-10 w-auto mx-auto">
                <img src="/images/cmremove.png" alt="Logo Icon"
                     x-show="collapsed"
                     x-transition.opacity
                     class="h-8 w-auto mx-auto">
            </div>

            <!-- Tombol Collapse (selalu di kanan) -->
            <button 
                @click="collapsed = !collapsed"
                class="ml-3 flex items-center justify-center p-1.5 rounded-md hover:bg-zinc-200 
                       dark:hover:bg-zinc-700 transition-colors"
                x-bind:title="collapsed ? 'Perluas Sidebar' : 'Sembunyikan Teks'">
                <!-- Ikon panah kiri-kanan -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                     fill="none" viewBox="0 0 24 24" 
                     stroke-width="1.5" stroke="currentColor" 
                     class="w-5 h-5 text-zinc-700 dark:text-zinc-200 transition-transform duration-300"
                     x-bind:class="collapsed ? 'rotate-180' : ''">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                </svg>
            </button>
        </div>

        <!-- MENU UTAMA + LOGOUT -->
        <nav class="flex-1 p-2 space-y-1 overflow-y-auto">
            <flux:navlist variant="outline">
                <div class="space-y-1" x-bind:class="collapsed ? 'px-1' : ''">

                    <!-- Dashboard -->
                    <flux:navlist.item 
                        icon="home"
                        :href="route('kasir.dashboard')" 
                        :current="request()->routeIs('kasir.dashboard')" 
                        wire:navigate
                        x-bind:class="collapsed ? 'justify-center px-3' : 'px-4'">
                        <span x-show="!collapsed" x-transition.opacity>Dashboard</span>
                    </flux:navlist.item>

                    <!-- Lihat Stok -->
                    <flux:navlist.item 
                        icon="archive-box"
                        :href="route('kasir.lihat-stok')" 
                        :current="request()->routeIs('kasir.lihat-stok')" 
                        wire:navigate
                        x-bind:class="collapsed ? 'justify-center px-3' : 'px-4'">
                        <span x-show="!collapsed" x-transition.opacity>Lihat Stok</span>
                    </flux:navlist.item>

                    <!-- Daftar Pesanan -->
                    <flux:navlist.item 
                        icon="queue-list"
                        :href="route('kasir.daftar-pesanan')" 
                        :current="request()->routeIs('kasir.daftar-pesanan')" 
                        wire:navigate
                        x-bind:class="collapsed ? 'justify-center px-3' : 'px-4'">
                        <span x-show="!collapsed" x-transition.opacity>Daftar Pesanan</span>
                    </flux:navlist.item>

                    <!-- Input Pesanan -->
                    <flux:navlist.item 
                        icon="plus-circle"
                        :href="route('kasir.input-pesanan')" 
                        :current="request()->routeIs('kasir.input-pesanan')" 
                        wire:navigate
                        x-bind:class="collapsed ? 'justify-center px-3' : 'px-4'">
                        <span x-show="!collapsed" x-transition.opacity>Input Pesanan</span>
                    </flux:navlist.item>
                    
                    <!-- Tampilan 
                    <flux:navlist.item 
                        icon="adjustments-horizontal"
                        :href="route('settings.appearance')" 
                        :current="request()->routeIs('settings.appearance')" 
                        wire:navigate
                        x-bind:class="collapsed ? 'justify-center px-3' : 'px-4'">
                        <span x-show="!collapsed" x-transition.opacity>Tema</span>
                    </flux:navlist.item> -->

                </div>
            </flux:navlist>

            <!-- LOGOUT -->
            <div class="mt-10 border-t border-zinc-200 dark:border-zinc-700 pt-4">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-3 w-full px-3 py-2 text-red-500 hover:text-red-700 
                                   transition-colors rounded-md"
                            x-bind:class="collapsed ? 'justify-center' : ''"
                            x-bind:title="collapsed ? 'Logout' : null">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             fill="none" viewBox="0 0 24 24" 
                             stroke-width="1.5" stroke="currentColor" 
                             class="w-5 h-5 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        <span x-show="!collapsed" x-transition.opacity>Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    {{-- KONTEN HALAMAN --}}
    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300">
        {{ $slot }}
    </main>

    @fluxScripts
</body>
</html>
