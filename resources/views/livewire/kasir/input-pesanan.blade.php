<div class="p-8 space-y-8 bg-white dark:bg-zinc-900 rounded-lg shadow">
    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-4">🧾 Input Pesanan</h2>

    @if (session('success'))
        <div class="bg-green-500 text-white p-3 rounded-md shadow-sm text-center">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="simpanPesanan" class="space-y-6">
        {{-- Nama Pelanggan --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Nama Pelanggan *
            </label>
            <input type="text" wire:model.live="namaPelanggan"
                class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-400 dark:bg-zinc-700 dark:text-white"
                placeholder="Masukkan nama pelanggan" required>
        </div>

        {{-- Item Pesanan --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Item Pesanan *</label>
            @foreach ($items as $index => $item)
                <div class="flex flex-wrap items-center gap-3 mb-3">
                    @php
                        $stok = !empty($item['idMenu']) ? \App\Models\Stok::where('idMenu', $item['idMenu'])->value('jumlahStok') ?? 0 : 0;
                    @endphp

                    <select wire:model.live="items.{{ $index }}.idMenu"
                        class="flex-1 px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-400 dark:bg-zinc-700 dark:text-white">
                        <option value="">Pilih Produk</option>
                        @foreach ($menuList as $menu)
                            @php
                                $stokMenu = \App\Models\Stok::where('idMenu', $menu->idMenu)->value('jumlahStok') ?? 0;
                            @endphp
                            <option value="{{ $menu->idMenu }}" {{ $stokMenu <= 0 ? 'disabled' : '' }}>
                                {{ $menu->namaMenu }} — Rp{{ number_format($menu->hargaProduk, 0, ',', '.') }}
                                {{ $stokMenu <= 0 ? '(Habis)' : "(Stok: $stokMenu)" }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number"
                        min="0"
                        max="{{ $stok }}"
                        wire:model.live="items.{{ $index }}.jumlah"
                        class="w-20 px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-400 dark:bg-zinc-700 dark:text-white"
                        {{ $stok <= 0 ? 'disabled' : '' }}
                        value="{{ $stok <= 0 ? 0 : ($item['jumlah'] ?? 1) }}">

                    <div class="text-gray-700 dark:text-gray-200 text-sm font-semibold w-20">
                        Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}
                    </div>

                    <select wire:model.live="items.{{ $index }}.idTopping"
                        class="flex-1 px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-purple-400 dark:bg-zinc-700 dark:text-white">
                        <option value="">Tanpa Topping</option>
                        @foreach ($toppingList as $topping)
                            <option value="{{ $topping->idTopping }}">{{ $topping->namaTopping }}</option>
                        @endforeach
                    </select>

                    <button type="button" wire:click="hapusItem({{ $index }})"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Hapus</button>
                </div>
            @endforeach

            <button type="button" wire:click="tambahItem"
                class="mt-3 px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md font-medium">
                + Tambah Item
            </button>
        </div>

        {{-- Total --}}
        <div class="bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 space-y-2 mt-6">
            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between font-bold text-lg text-gray-900 dark:text-white border-t pt-2">
                <span>Total Pembayaran:</span>
                <span class="text-blue-600 dark:text-blue-400">
                    Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Metode Pembayaran *</label>
            <select wire:model.live="metodePembayaran"
                class="w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-green-400 dark:bg-zinc-700 dark:text-white">
                <option value="">Pilih Metode</option>
                <option value="Tunai">Tunai</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>

        {{-- Catatan --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Catatan Khusus</label>
            <textarea wire:model.live="catatan"
                class="w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-400 dark:bg-zinc-700 dark:text-white"
                rows="3" placeholder="Contoh: tanpa mayo, tambah saus BBQ..."></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <button type="button"
                class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md font-semibold">
                Batal
            </button>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-md font-semibold">
                Simpan Pesanan
            </button>
        </div>
    </form>

    {{-- JS Notifikasi --}}
    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.on('stokError', message => {
                alert(message);
            });
        });
    </script>
</div>
