<div class="min-h-screen w-full flex flex-col items-center bg-gray-50 px-4 py-10">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">🛒 Keranjang Pesanan</h1>
            <button wire:click="kembaliKeMenu"
                class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-lg font-semibold text-gray-700">
                ← Kembali ke Menu
            </button>
        </div>

        @if (empty($cart))
            <p class="text-center text-gray-500 py-10 text-lg">
                Keranjang masih kosong.
            </p>
        @else
            {{-- Daftar Item --}}
            <div class="overflow-x-auto mb-6">
                <table class="w-full border-collapse">
                    <thead class="bg-blue-600 text-white text-sm uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left">Menu</th>
                            <th class="py-3 px-4 text-center">Jumlah</th>
                            <th class="py-3 px-4 text-left">Topping</th>
                            <th class="py-3 px-4 text-right">Subtotal</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($cart as $index => $item)
                            <tr>
                                <td class="py-3 px-4 text-gray-800 font-semibold">
                                    {{ $item['namaMenu'] }}
                                </td>
                                <td class="py-3 px-4 text-center text-gray-700">
                                    {{ $item['jumlah'] }}
                                </td>
                                <td class="py-3 px-4 text-gray-600 text-sm">
                                    {{ $item['topping'] ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-right text-gray-900 font-semibold">
                                    Rp {{ number_format($item['total'], 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-center flex justify-center gap-2">
                                    <button wire:click="editItem({{ $index }})"
                                        class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded-md">
                                        Edit
                                    </button>
                                    <button wire:click="hapusItem({{ $index }})"
                                        class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Ringkasan --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 space-y-3">
                <div class="flex justify-between text-gray-700">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                @if ($isDelivery)
                    <div class="flex justify-between text-gray-700">
                        <span>Biaya Pengantaran</span>
                        <span>Rp {{ number_format($biayaKirim, 0, ',', '.') }}</span>
                    </div>

                    <div class="text-sm text-gray-500">
                        <p>Jarak dari toko: <strong>{{ $jarakKeToko }} km</strong></p>
                        @if ($biayaKirim == 0 && $jarakKeToko <= 1)
                            <p>✅ Gratis ongkir untuk jarak ≤ 1 km!</p>
                        @elseif ($jarakKeToko > 10)
                            <p class="text-red-500">❌ Di luar jangkauan pengantaran (>10 km)</p>
                        @endif
                    </div>
                @endif

                <div class="border-t pt-3 flex justify-between font-bold text-lg text-gray-900">
                    <span>Total</span>
                    <span class="text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Form Identitas --}}
            <div class="mt-8 space-y-4">
                @if (!$isDelivery)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Pelanggan *</label>
                        <input type="text" wire:model="namaPelanggan"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan nama pelanggan">
                        @error('namaPelanggan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP *</label>
                        <input type="text" wire:model="noHP"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan nomor HP">
                        @error('noHP')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-2 text-gray-600 text-sm">
                        <p><strong>Alamat Pengantaran:</strong> {{ $alamatPelanggan }}</p>
                    </div>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-6 flex justify-end gap-3">
                <button wire:click="lanjutPembayaran"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">
                    Lanjut Pembayaran →
                </button>
            </div>
        @endif
    </div>
</div>
