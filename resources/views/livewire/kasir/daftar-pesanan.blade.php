<div class="p-10 space-y-10 relative" wire:poll.5s="refreshPesanan">
    <h2 class="text-3xl font-bolder text-gray-800 dark:text-gray-100">Daftar Pesanan</h2>

    @if (empty($pesananList))
        <p class="text-center text-gray-600 dark:text-gray-300 py-12 text-lg">
            Belum ada pesanan.
        </p>
    @else
        @foreach ($pesananList as $tanggal => $pesananPerTanggal)
            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-gray-200 dark:border-zinc-700 p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                        {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
                    </h3>
                    @if (\Carbon\Carbon::parse($tanggal)->isToday())
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700">Hari Ini</span>
                    @endif
                </div>

                <table class="w-full border-collapse text-sm">
                    <thead class="bg-blue-600 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-left">No</th>
                            <th class="px-6 py-4 text-left">Pelanggan</th>
                            <th class="px-6 py-4 text-left">Detail Pesanan</th>
                            <th class="px-6 py-4 text-center">Total</th>
                            <th class="px-6 py-4 text-center">Catatan</th>
                            <th class="px-6 py-4 text-center">Pengantaran</th>
                            <th class="px-6 py-4 text-center">Status Pesanan</th>
                            <th class="px-6 py-4 text-center">Metode Pembayaran</th>
                            <th class="px-6 py-4 text-center">Status Pembayaran</th>
                            <th class="px-6 py-4 text-center">Bukti</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @if (count($pesananPerTanggal) === 0)
                            <tr>
                                <td colspan="11" class="text-center py-6 text-gray-500 italic">
                                    Tidak ada pesanan pada tanggal ini.
                                </td>
                            </tr>
                        @else
                            @foreach ($pesananPerTanggal as $index => $pesanan)
                                <tr class="hover:bg-blue-50 dark:hover:bg-zinc-800 transition duration-200">
                                    <td class="px-6 py-5 font-medium text-gray-800 dark:text-gray-200">{{ $index + 1 }}</td>
                                    <td class="px-6 py-5 text-gray-700 dark:text-gray-300">{{ $pesanan->pelanggan->namaPelanggan ?? 'Walk-in Customer' }}</td>

                                    <td class="px-6 py-5 text-gray-800 dark:text-gray-200">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($pesanan->items as $item)
                                                <li>
                                                    {{ $item->jumlahPesanan }}x {{ $item->menu->namaMenu ?? 'Menu Dihapus' }}
                                                    @if ($item->topping)
                                                        <span class="text-xs text-gray-500">+ {{ $item->topping->namaTopping }}</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>

                                <td class="px-6 py-5 text-center font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($pesanan->totalPembayaran ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-5 text-center text-gray-600 dark:text-gray-300">
                                    @php
                                        $catatanList = $pesanan->items->pluck('catatanPesanan')->filter()->implode(', ');
                                    @endphp
                                    {{ $catatanList ?: '-' }}
                                </td>

                                <td class="px-6 py-5 text-center">
                                    @if ($pesanan->lokasiGPS)
                                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">Dikirim</span>
                                    @else
                                        <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 font-semibold">Ambil di Tempat</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5 text-center">
                                    <select wire:change="ubahStatus({{ $pesanan->idPesanan }}, $event.target.value)"
                                        class="px-4 py-2 rounded-lg border border-gray-300 dark:bg-zinc-700 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
                                        @if ($pesanan->lokasiGPS)
                                            <option {{ $pesanan->statusPesanan === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option {{ $pesanan->statusPesanan === 'Sedang Dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                                            <option {{ $pesanan->statusPesanan === 'Sudah Sampai' ? 'selected' : '' }}>Sudah Sampai</option>
                                            <option {{ $pesanan->statusPesanan === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        @else
                                            <option {{ $pesanan->statusPesanan === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option {{ $pesanan->statusPesanan === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        @endif
                                    </select>
                                </td>

                                <td class="px-6 py-5 text-center">
                                    @if ($pesanan->pembayaran)
                                        <span class="px-4 py-1 text-xs font-semibold rounded-full
                                            {{ $pesanan->pembayaran->metodePembayaran === 'QRIS'
                                                ? 'bg-purple-100 text-purple-700'
                                                : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ strtoupper($pesanan->pembayaran->metodePembayaran) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic text-sm">Belum dipilih</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5 text-center">
                                    <select wire:change="ubahStatusPembayaran({{ $pesanan->idPesanan }}, $event.target.value)"
                                        class="px-4 py-2 rounded-lg border border-gray-300 dark:bg-zinc-700 dark:text-white text-sm focus:ring-2 focus:ring-green-500">
                                        <option value="Belum Dibayar" {{ $pesanan->pembayaran?->statusPembayaran === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                        <option value="Sudah Dibayar" {{ $pesanan->pembayaran?->statusPembayaran === 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                                    </select>
                                </td>

                                <td class="px-6 py-5 text-center">
                                    @if ($pesanan->pembayaran && $pesanan->pembayaran->metodePembayaran === 'QRIS')
                                        @if ($pesanan->pembayaran->buktiPembayaran)
                                            <button wire:click="lihatBukti({{ $pesanan->idPesanan }})"
                                                class="text-blue-500 underline hover:text-blue-700">
                                                Lihat Bukti
                                            </button>
                                        @else
                                            <span class="text-gray-400 italic text-sm">Belum diunggah</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic text-sm">Tidak Berlaku</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5 text-center">
                                    <button wire:click="toggleDetail({{ $pesanan->idPesanan }})"
                                        class="px-4 py-2 bg-blue-600 rounded-md hover:bg-blue-700 text-sm transition">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif

    {{-- MODAL DETAIL PESANAN --}}
    @if ($showDetail && $selectedPesanan)
        <div class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 w-[700px] max-h-[90vh] overflow-y-auto border border-gray-200 dark:border-zinc-700">
                <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                    Detail Pesanan #{{ $selectedPesanan->idPesanan }}
                </h3>

                <div class="space-y-4">
                    <p><strong>Pelanggan:</strong> {{ $selectedPesanan->pelanggan->namaPelanggan ?? 'Walk-in Customer' }}</p>
                    <p><strong>Nomor Antrean:</strong> 
                        <span class="font-semibold text-blue-600">{{ $selectedPesanan->noAntrean ?? '-' }}</span>
                    </p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($selectedPesanan->tanggalPesanan)->format('d M Y H:i') }}</p>
                    <p><strong>Metode Pengantaran:</strong>
                        @if ($selectedPesanan->lokasiGPS)
                            <span class="text-blue-600 font-semibold">Dikirim</span>
                        @else
                            <span class="text-gray-700 font-semibold">Ambil di Tempat</span>
                        @endif
                    </p>
                    <p><strong>Nomor HP:</strong> {{ $selectedPesanan->pelanggan->noHP ?? '-' }}</p>

                    @if ($selectedPesanan->lokasiGPS)
                        <div class="mt-2 space-y-2">
                            <p><strong>Alamat Pengantaran:</strong><br>
                                <span class="text-gray-800 dark:text-gray-200">
                                    {{ $selectedPesanan->pelanggan->alamat ?? '-' }}
                                </span>
                            </p>

                            <p><strong>📍 Lokasi Pelanggan:</strong></p>
                            <button onclick="window.open('https://www.google.com/maps?q={{ $selectedPesanan->lokasiGPS }}', '_blank')"
                                class="mt-2 w-full bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 font-semibold py-2 rounded-lg shadow-md transition duration-200">
                                Lihat di Google Maps
                            </button>
                        </div>
                    @endif
                </div>

                <div class="mt-8 text-right">
                    <button wire:click="$set('showDetail', false)"
                        class="px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL BUKTI PEMBAYARAN --}}
    @if ($showBukti && $buktiUrl)
        <div class="modal-bukti fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50"
            wire:click="$set('showBukti', false)">
            <div wire:click.stop
                class="modal-container relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-gray-300 dark:border-zinc-700 p-6">
                
                <button wire:click="$set('showBukti', false)"
                    class="absolute top-2 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-2xl font-bold">&times;</button>

                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white text-center">
                    Bukti Pembayaran QRIS
                </h3>

                <div class="flex justify-center items-center">
                    <img src="{{ $buktiUrl }}" 
                        alt="Bukti Pembayaran"
                        class="cursor-pointer"
                        onclick="window.open('{{ $buktiUrl }}', '_blank')">
                </div>
            </div>
        </div>
    @endif

    {{-- Scoped CSS --}}
    <style>
        .modal-bukti img {
            max-width: 95%;
            max-height: 520px;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .modal-bukti img:hover {
            transform: scale(1.05);
        }

        .modal-bukti .modal-container {
            width: 90%;
            max-width: 700px;
            max-height: 95vh;
            border-radius: 16px;
            overflow: hidden;
        }
    </style>
</div>
