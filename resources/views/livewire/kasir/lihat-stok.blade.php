<div class="w-full mt-6 overflow-x-auto">
    <h2 class="text-xl font-semibold mb-4">Lihat Stok</h2>

    @if (session('message'))
        <div class="mb-4 p-3 bg-green-500 text-white rounded-md">
            {{ session('message') }}
        </div>
    @endif

    <table class="w-full text-sm text-left border-collapse">
        <thead class="bg-zinc-200 dark:bg-zinc-700 text-gray-900 dark:text-gray-100">
            <tr>
                <th class="px-6 py-3 font-semibold">Nama Menu</th>
                <th class="px-6 py-3 font-semibold text-center">Stok Tersedia</th>
                <th class="px-6 py-3 font-semibold text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100">
            @foreach ($stok as $item)
                <tr class="border-t border-zinc-300 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600 transition">
                    <td class="px-6 py-3">{{ $item->menu->namaMenu ?? '-' }}</td>
                    <td class="px-6 py-3 text-center">
                        @if ($item->jumlahStok == 0)
                            <span class="text-red-500 font-semibold">Habis</span>
                        @else
                            {{ $item->jumlahStok }}
                        @endif
                    </td>
                    <td class="px-6 py-3 text-center space-x-2">
                        {{-- Tombol Edit --}}
                        <button
                            wire:click="openModal({{ $item->idStok }}, false)"
                            class="px-3 py-1 rounded-md transition
                            {{ $item->jumlahStok == 0 ? 'bg-gray-400 text-gray-200 cursor-not-allowed' : 'bg-blue-500 text-white hover:bg-blue-600' }}"
                            {{ $item->jumlahStok == 0 ? 'disabled' : '' }}>
                            Edit
                        </button>

                        {{-- Tombol Input --}}
                        <button
                            wire:click="openModal({{ $item->idStok }}, true)"
                            class="px-3 py-1 rounded-md transition
                            {{ $item->jumlahStok == 0 ? 'bg-green-500 text-white hover:bg-green-600' : 'bg-gray-400 text-gray-200 cursor-not-allowed' }}"
                            {{ $item->jumlahStok > 0 ? 'disabled' : '' }}>
                            Input
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    {{-- MODAL --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl p-6 w-96 shadow-2xl transform transition-all duration-200 scale-100">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100 text-center">
                    {{ $isInputMode ? '🟢 Input Stok Baru' : '✏️ Edit Stok' }}<br>
                    <span class="text-blue-600 dark:text-blue-400 text-base">
                        {{ $selectedStok->menu->namaMenu ?? '' }}
                    </span>
                </h3>

                <input
                    type="number"
                    wire:model="jumlahBaru"
                    placeholder="Masukkan jumlah stok"
                    class="w-full mb-4 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-zinc-700 dark:text-white"
                >

                <div class="flex justify-end space-x-2">
                    <button
                        wire:click="$set('showModal', false)"
                        class="px-4 py-2 bg-gray-400 rounded-md hover:bg-gray-500 transition">
                        Batal
                    </button>

                    <button
                        wire:click="saveStok"
                        class="px-4 py-2 bg-blue-600 rounded-md hover:bg-blue-700 transition">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
