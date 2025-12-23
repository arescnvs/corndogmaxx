<div class="p-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">🏪 Dashboard Kasir</h2>

    {{-- Statistik utama --}}
    <div class="grid md:grid-cols-4 gap-6">
        {{-- Total Stok --}}
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow border border-gray-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-blue-600 mb-2">Total Stok</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalStok }}</p>
        </div>

        {{-- Pesanan Diproses --}}
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow border border-gray-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-yellow-600 mb-2">Pesanan Diproses</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pesananDiproses }}</p>
        </div>

        {{-- Pesanan Selesai --}}
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow border border-gray-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-green-600 mb-2">Pesanan Selesai</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pesananSelesai }}</p>
        </div>

        {{-- Pendapatan Hari Ini --}}
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl shadow border border-gray-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-purple-600 mb-2">Pendapatan Hari Ini</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Ringkasan Penjualan Minggu Ini --}}
    <div class="mt-10 bg-white dark:bg-zinc-900 p-10 rounded-xl shadow border border-gray-200 dark:border-zinc-700">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
            📊 Ringkasan Penjualan Minggu Ini 
            <span class="text-sm text-gray-500 dark:text-gray-400 font-normal">
                ({{ $mingguMulai->format('d M') }} – {{ $mingguAkhir->format('d M Y') }})
            </span>
        </h3>

        <table class="w-full text-base text-left border-collapse">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 uppercase text-xs">
                <tr>
                    <th class="px-10 py-4">Tanggal</th>
                    <th class="px-10 py-4 text-center">Total Pesanan</th>
                    <th class="px-10 py-4 text-right">Total Pendapatan</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @php $totalKeseluruhan = 0; @endphp

                @forelse ($ringkasanPenjualan as $data)
                    @php
                        $isToday = \Carbon\Carbon::parse($data['tanggal'])->isToday();
                        $totalKeseluruhan += $data['total_pendapatan'];
                    @endphp
                    <tr class="{{ $isToday ? 'bg-blue-50 dark:bg-zinc-800/60' : '' }}">
                        <td class="px-10 py-4 font-medium">
                            {{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('D, d M Y') }}
                            @if ($isToday)
                                <span class="ml-2 text-xs text-blue-600 dark:text-blue-400 font-semibold">(Hari Ini)</span>
                            @endif
                        </td>
                        <td class="px-10 py-4 text-center">{{ $data['total_pesanan'] }}</td>
                        <td class="px-10 py-4 text-right text-green-600 font-semibold">
                            Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-10 py-4 text-center text-gray-500 dark:text-gray-400">
                            Belum ada data penjualan minggu ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>

            @if (count($ringkasanPenjualan) > 0)
                <tfoot class="bg-gray-50 dark:bg-zinc-800 text-gray-800 dark:text-gray-100 font-semibold">
                    <tr>
                        <td class="px-10 py-4">Total Minggu Ini</td>
                        <td class="px-10 py-4 text-center">
                            {{ collect($ringkasanPenjualan)->sum('total_pesanan') }}
                        </td>
                        <td class="px-10 py-4 text-right text-green-500">
                            Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
