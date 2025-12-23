<?php

namespace App\Livewire\Kasir;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Stok;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app.sidebar')]
class Dashboard extends Component
{
    public $totalStok;
    public $pesananDiproses;
    public $pesananSelesai;
    public $totalPesanan;
    public $pendapatanHariIni;
    public $ringkasanPenjualan = [];
    public $mingguMulai;
    public $mingguAkhir;

    public function mount()
    {
        $today = Carbon::today();
        // Total stok
        $this->totalStok = Stok::sum('jumlahStok');

        // Jumlah pesanan
        $this->pesananDiproses = Pesanan::whereDate('tanggalPesanan', $today) ->where('statusPesanan', 'Diproses') ->count();
        $this->pesananSelesai = Pesanan::whereDate('tanggalPesanan', $today) ->where('statusPesanan', 'Selesai') ->count();
        $this->totalPesanan = Pesanan::whereDate('tanggalPesanan', $today)->count();

        // Pendapatan hari ini
        $this->pendapatanHariIni = $this->hitungPendapatanHariIni();

        // Rentang minggu ini
        $this->mingguMulai = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $this->mingguAkhir = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        // Ringkasan penjualan untuk minggu ini
        $this->ringkasanPenjualan = $this->ambilRingkasanPenjualanMingguIni();
    }

    private function hitungPendapatanHariIni()
    {
        $today = Carbon::today();
        $pesananHariIni = Pesanan::with(['pembayaran'])
            ->whereDate('tanggalPesanan', $today)
            ->get();

        $totalPendapatan = 0;

        foreach ($pesananHariIni as $pesanan) {
            $pembayaran = $pesanan->pembayaran;
            if (! $pembayaran) continue;

            if ($pembayaran->metodePembayaran === 'QRIS' && $pembayaran->statusPembayaran === 'Sudah Dibayar') {
                $totalPendapatan += $pesanan->totalPembayaran;
            } elseif ($pembayaran->metodePembayaran === 'Tunai') {
                if ($pesanan->lokasiGPS && $pesanan->statusPesanan === 'Sudah Sampai') {
                    $totalPendapatan += $pesanan->totalPembayaran;
                } elseif (! $pesanan->lokasiGPS && $pembayaran->statusPembayaran === 'Sudah Dibayar') {
                    $totalPendapatan += $pesanan->totalPembayaran;
                }
            }
        }

        return $totalPendapatan;
    }

    private function ambilRingkasanPenjualanMingguIni()
    {
        $startOfWeek = $this->mingguMulai;
        $endOfWeek = $this->mingguAkhir;

        // Ambil data dari database (yang sudah dibayar)
        $penjualan = Pesanan::select(
                DB::raw('DATE(tanggalPesanan) as tanggal'),
                DB::raw('COUNT(*) as total_pesanan'),
                DB::raw('SUM(totalPembayaran) as total_pendapatan')
            )
            ->whereBetween('tanggalPesanan', [$startOfWeek, $endOfWeek])
            ->whereHas('pembayaran', function ($query) {
                $query->where('statusPembayaran', 'Sudah Dibayar');
            })
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->keyBy('tanggal');

        // Lengkapi daftar Senin–Minggu
        $dataLengkap = collect();
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $tanggal = $date->toDateString();
            $dataLengkap->push([
                'tanggal' => $tanggal,
                'total_pesanan' => $penjualan[$tanggal]->total_pesanan ?? 0,
                'total_pendapatan' => $penjualan[$tanggal]->total_pendapatan ?? 0,
            ]);
        }

        return $dataLengkap;
    }

    public function render()
    {
        return view('livewire.kasir.dashboard', [
            'ringkasanPenjualan' => $this->ringkasanPenjualan,
            'mingguMulai' => $this->mingguMulai,
            'mingguAkhir' => $this->mingguAkhir,
        ]);
    }
}
