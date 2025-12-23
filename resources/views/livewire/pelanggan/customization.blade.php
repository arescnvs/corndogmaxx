<div class="min-h-screen w-full flex flex-col items-center bg-gray-50 px-4 py-10">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center mb-6">
            <button wire:click="kembaliKeMenu"
                class="text-gray-500 hover:text-gray-700 font-semibold flex items-center">
                ← Kembali
            </button>
        </div>

        <h1 class="text-2xl font-extrabold text-gray-800 mb-4 text-center">
            Kustomisasi Pesanan
        </h1>

        @if ($menu)
            <div class="text-center mb-6">
                <img
                    src="{{ asset('images/' . $menu->idMenu . '.jpeg') }}"
                    onerror="this.onerror=null; this.src='https://placehold.co/300x200?text=No+Image';"
                    alt="{{ $menu->namaMenu }}"
                    class="w-full h-40 object-cover rounded-lg mb-4"
                />
                <h2 class="font-semibold text-gray-800 text-lg">{{ $menu->namaMenu }}</h2>
                <p class="text-gray-500">
                    Rp {{ number_format($menu->hargaProduk, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-sm {{ $stokTersedia > 0 ? 'text-green-600' : 'text-red-500 font-semibold' }}">
                    {{ $stokTersedia > 0 ? "Stok tersedia: $stokTersedia" : 'Stok Habis' }}
                </p>
            </div>

            {{-- Pilih Topping --}}
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">Pilih Topping</h3>
                <div class="space-y-2">
                    @foreach ($toppingList as $topping)
                        <label class="flex items-center space-x-3">
                            <input type="radio" wire:model="selectedTopping" value="{{ $topping->idTopping }}">
                            <span class="text-gray-700">{{ $topping->namaTopping }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Jumlah --}}
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">Jumlah</h3>
                <div class="flex items-center justify-center space-x-4">
                    <button wire:click="ubahJumlah(-1)"
                        class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 text-xl font-bold"
                        {{ $stokTersedia <= 0 ? 'disabled class=cursor-not-allowed opacity-50' : '' }}>−</button>

                    <span class="text-xl font-semibold">{{ $jumlah }}</span>

                    <button wire:click="ubahJumlah(1)"
                        class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 text-xl font-bold"
                        {{ $stokTersedia <= 0 ? 'disabled class=cursor-not-allowed opacity-50' : '' }}>+</button>
                </div>
            </div>

            {{-- Catatan --}}
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">Catatan</h3>
                <textarea wire:model="catatan"
                    placeholder="Contoh: tanpa saus sambal"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"></textarea>
            </div>

            {{-- Tombol Tambah --}}
            <button wire:click="tambahKeKeranjang"
                class="w-full py-3 rounded-lg font-semibold transition
                    {{ $stokTersedia > 0 ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                {{ $stokTersedia <= 0 ? 'disabled' : '' }}>
                {{ $stokTersedia > 0 ? 'Tambahkan ke Keranjang' : 'Stok Habis' }}
            </button>
        @else
            <p class="text-center text-gray-500">Menu tidak ditemukan.</p>
        @endif
    </div>

    {{-- JS Alert untuk batas stok --}}
    <script>
        window.addEventListener('stokLimit', event => {
            alert(event.detail.message);
        });
    </script>
</div>
