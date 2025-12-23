<div class="min-h-screen w-full flex items-center justify-center bg-gray-50 px-4 py-10">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 text-center">
        <h1 class="text-2xl font-extrabold text-gray-800 mb-6">Metode Pembayaran</h1>

        <div class="space-y-4 mb-6">
            <button wire:click="pilihMetode('QRIS')"
                class="w-full py-3 rounded-lg border border-gray-300 text-lg font-semibold 
                {{ $metodePembayaran === 'QRIS' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-50 text-gray-800 hover:bg-gray-100' }}">
                QRIS
            </button>

            <button wire:click="pilihMetode('Tunai')"
                class="w-full py-3 rounded-lg border border-gray-300 text-lg font-semibold 
                {{ $metodePembayaran === 'Tunai' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-50 text-gray-800 hover:bg-gray-100' }}">
                Tunai (Bayar di Tempat)
            </button>
        </div>

        @if ($metodePembayaran === 'QRIS')
            <div class="mb-6">
                <p class="text-gray-600 mb-2">Scan kode QR berikut untuk membayar:</p>
                <img src="{{ asset('images/qris.jpeg') }}" alt="QRIS" class="mx-auto w-48 rounded-lg shadow-sm mb-3">
                <input type="file" wire:model="buktiPembayaran" accept="image/*" class="mt-2 block w-full text-sm text-gray-600 border border-gray-300 rounded-lg p-2" />
                @error('buktiPembayaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="mt-6">
            <p class="font-semibold text-lg text-gray-700 mb-4">Rincian Pembayaran</p>

            {{-- subtotal --}}
            <div class="flex justify-between text-gray-700 mb-1">
                <span>Subtotal</span>
                <span>Rp {{ number_format($total - $biayaKirim, 0, ',', '.') }}</span>
            </div>

            {{-- biaya kirim jika ada --}}
            @if ($alamatPelanggan)
                <div class="flex justify-between text-gray-700 mb-1">
                    <span>Biaya Pengantaran</span>
                    <span>Rp {{ number_format($biayaKirim, 0, ',', '.') }}</span>
                </div>
            @endif

            <hr class="my-3">

            {{-- total akhir --}}
            <div class="flex justify-between text-gray-900 font-bold text-xl mb-6">
                <span>Total Bayar</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="flex flex-col space-y-3">
                <button wire:click="konfirmasiPembayaran"
                    class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-150">
                    Konfirmasi Pembayaran
                </button>

                <button wire:click="kembaliKeKeranjang"
                    class="w-full py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition duration-150">
                    ← Kembali ke Keranjang
                </button>
            </div>
        </div>
    </div>
</div>
