<?php

namespace App\Livewire\Kasir;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Carbon\Carbon;

#[Layout('components.layouts.app.sidebar')]
class DaftarPesanan extends Component
{
    public $showDetail = false;
    public $selectedPesanan = null;
    public $showBukti = false;
    public $buktiUrl = null;
    public $pesananList = [];
    private $lastCount = 0;

    public function mount()
    {
        $this->refreshPesanan();
        $this->lastCount = count($this->pesananList);
    }

    public function refreshPesanan()
    {
        // Ambil semua pesanan terbaru
        $pesanan = Pesanan::with([
            'pelanggan',
            'items.menu:idMenu,namaMenu,hargaProduk',
            'items.topping:idTopping,namaTopping',
            'pembayaran:idPembayaran,idPesanan,metodePembayaran,statusPembayaran,buktiPembayaran'
        ])
            ->latest()
            ->take(100)
            ->get();

        // Group berdasarkan tanggal
        $grouped = $pesanan
            ->groupBy(fn($item) => Carbon::parse($item->tanggalPesanan)->format('Y-m-d'))
            ->map(fn($group) => $group->values()->all())
            ->toArray();

        // Tentukan rentang tanggal (misal 7 hari terakhir)
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        $fullDateRange = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $tanggalKey = $date->format('Y-m-d');
            $fullDateRange[$tanggalKey] = $grouped[$tanggalKey] ?? [];
        }

        // Urutkan dari tanggal terbaru ke terlama
        $this->pesananList = collect($fullDateRange)
            ->sortKeysDesc()
            ->toArray();
    }

    public function ubahStatus($id, $statusBaru = null)
    {
        $pesanan = Pesanan::find($id);
        if ($pesanan) {
            $pesanan->statusPesanan = $statusBaru ?? 'Diproses';
            $pesanan->save();
            $this->refreshPesanan();
        }
    }

    public function ubahStatusPembayaran($idPesanan, $statusBaru)
    {
        $pesanan = Pesanan::with('pembayaran')->find($idPesanan);
        if (!$pesanan || !$pesanan->pembayaran) return;

        $pesanan->pembayaran->update(['statusPembayaran' => $statusBaru]);

        // buat nomor antrean jika baru dibayar
        if ($statusBaru === 'Sudah Dibayar' && empty($pesanan->noAntrean)) {
            $nomorTerakhir = Pesanan::whereDate('tanggalPesanan', now()->toDateString())
                ->whereNotNull('noAntrean')
                ->max('noAntrean');
            $pesanan->update(['noAntrean' => $nomorTerakhir ? $nomorTerakhir + 1 : 1]);
        }

        $this->refreshPesanan();
    }

    public function lihatBukti($id)
    {
        $pembayaran = Pembayaran::where('idPesanan', $id)->first();
        if (!$pembayaran || !$pembayaran->buktiPembayaran) return;
        $this->buktiUrl = asset('storage/' . $pembayaran->buktiPembayaran);
        $this->showBukti = true;
    }

    public function toggleDetail($id)
    {
        $this->selectedPesanan = Pesanan::with(['items.menu', 'items.topping', 'pelanggan', 'pembayaran'])->find($id);
        $this->showDetail = true;
    }

    public function render()
    {
        return view('livewire.kasir.daftar-pesanan', [
            'pesananList' => $this->pesananList,
        ]);
    }
}
