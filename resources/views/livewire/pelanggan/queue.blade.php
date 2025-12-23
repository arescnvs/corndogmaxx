<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4"
     wire:poll.5s="refreshStatus">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md text-center">
        
        {{-- 🔹 Jika pesanan dikirim --}}
        @if ($metodePengantaran === 'dikirim')

            {{-- Belum dibayar --}}
            @if ($statusPembayaran !== 'Sudah Dibayar')
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Menunggu Konfirmasi Pembayaran</h1>
                <p class="text-gray-500 mb-6">Kasir sedang memverifikasi pembayaran Anda...</p>

            {{-- Sudah dibayar --}}
            @else
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Status Pengiriman Pesanan</h1>

                <div class="mb-4">
                    <div class="text-xl font-semibold 
                        @if ($statusPesanan === 'Diproses') 
                        @elseif ($statusPesanan === 'Selesai') 
                        @elseif ($statusPesanan === 'Sedang Dikirim') 
                        @elseif ($statusPesanan === 'Sudah Sampai')
                        @else text-gray-600 @endif">
                        {{ ucfirst($statusPesanan) }}
                    </div>

                    <p class="text-gray-500 mt-2">
                        @if ($statusPesanan === 'Diproses')
                            Pesanan Anda sedang disiapkan oleh tim dapur 🍳
                        @elseif ($statusPesanan === 'Selesai')
                            Pesanan Anda sudah selesai dibuat dan menunggu pengiriman 🚗
                        @elseif ($statusPesanan === 'Sedang Dikirim')
                            Pesanan Anda sedang dalam perjalanan 📦
                        @elseif ($statusPesanan === 'Sudah Sampai')
                            Pesanan Anda sudah sampai di alamat tujuan ✅
                        @else
                            Menunggu konfirmasi kasir...
                        @endif
                    </p>
                </div>

                {{-- Estimasi waktu --}}
                @if ($estimasiWaktu)
                    <div class="mt-4 text-sm text-gray-600">
                        ⏰  
                        <span class="font-semibold text-gray-800">{{ $estimasiWaktu }}</span>
                    </div>
                @endif
            @endif

        {{-- 🔹 Jika pesanan di tempat --}}
        @else
            @if ($statusPembayaran === 'Sudah Dibayar' && $noAntrean)
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Nomor Antrean Anda</h1>
                <div class="text-6xl font-extrabold text-blue-600 mb-4">{{ $noAntrean }}</div>

                @if ($statusPesanan === 'Diproses')
                    <p class="text-gray-600 mb-6">Pesanan Anda sedang diproses...</p>
                @elseif ($statusPesanan === 'Selesai')
                    <p class="text-green-600 font-semibold mb-6">
                        Pesanan Anda sudah selesai! Silakan ambil di kasir.
                    </p>
                @else
                    <p class="text-gray-500 mb-6">Menunggu konfirmasi kasir...</p>
                @endif

            @else
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Menunggu Konfirmasi Pembayaran</h1>
                <p class="text-gray-500 mb-6">Kasir sedang memverifikasi pembayaran Anda...</p>
            @endif
        @endif

        {{-- Auto-refresh info --}}
        <div class="text-sm text-gray-400 mt-4">
            (Halaman ini otomatis diperbarui setiap 5 detik)
        </div>

        {{-- Tombol kembali ke menu --}}
        <div class="mt-8">
            <a href="{{ route('pelanggan.menu') }}"
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kembali ke Menu
            </a>
        </div>
    </div>
</div>
