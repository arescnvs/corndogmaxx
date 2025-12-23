<div class="min-h-screen w-full flex flex-col items-center bg-gray-50 px-4 py-10">
    @if (session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4 text-center">
        {{ session('success') }}
    </div>
    @endif

    <div class="w-full max-w-5xl">
        <h1 class="text-2xl font-extrabold text-gray-800 mb-8 text-center">
            Pilih Menu Corndog
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($menuList as $menu)
                @php
                    $stok = \App\Models\Stok::where('idMenu', $menu->idMenu)->value('jumlahStok') ?? 0;
                @endphp
                <div class="bg-white rounded-xl shadow-md p-4 text-center">
                    <img src="{{ asset('images/' . $menu->idMenu . '.jpeg') }}"
                        onerror="this.onerror=null; this.src='https://placehold.co/300x200?text=No+Image';"
                        alt="{{ $menu->namaMenu }}"
                        class="w-full h-40 object-cover rounded-lg mb-4"
                    />
                    <h2 class="font-semibold text-gray-800">{{ $menu->namaMenu }}</h2>
                    <p class="text-gray-500 mb-2">Rp {{ number_format($menu->hargaProduk, 0, ',', '.') }}</p>
                    <p class="text-sm {{ $stok > 0 ? 'text-green-600' : 'text-red-500 font-semibold' }}">
                        {{ $stok > 0 ? "Stok: $stok" : 'Stok Habis' }}
                    </p>

                    <button wire:click="{{ $stok > 0 ? 'pilihMenu(' . $menu->idMenu . ')' : '' }}"
                        class="w-full py-2 mt-2 rounded-lg font-semibold transition
                            {{ $stok > 0 ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                        {{ $stok > 0 ? '' : 'disabled' }}>
                        {{ $stok > 0 ? 'Pilih Menu Ini' : 'Tidak Tersedia' }}
                    </button>
                </div>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <button wire:click="lihatKeranjang"
                class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                Lihat Keranjang
            </button>
        </div>
    </div>
</div>
