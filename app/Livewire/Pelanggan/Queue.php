<?php

namespace App\Livewire\Pelanggan;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Pesanan;
use Carbon\Carbon;

#[Layout('pelanggan')]
class Queue extends Component
{
    public $pesanan;
    public $idPesanan;
    public $statusPembayaran;
    public $statusPesanan;
    public $noAntrean;
    public $metodePengantaran = 'ditempat';
    public $estimasiWaktu;

    // Koordinat toko
    private float $tokoLat = -6.295251739177259;
    private float $tokoLng = 106.73509377079432;

    public function mount($id)
    {
        $this->idPesanan = $id;
        $this->refreshStatus();
    }

    public function refreshStatus()
    {
        $this->pesanan = Pesanan::with('pembayaran', 'pelanggan')
            ->find($this->idPesanan);

        if ($this->pesanan) {
            $this->statusPembayaran = $this->pesanan->pembayaran->statusPembayaran ?? 'Belum Dibayar';
            $this->statusPesanan = $this->pesanan->statusPesanan ?? 'Menunggu';
            $this->noAntrean = $this->pesanan->noAntrean;
            $this->metodePengantaran = $this->pesanan->lokasiGPS ? 'dikirim' : 'ditempat';
            $this->estimasiWaktu = $this->hitungEstimasi();
        }
    }

    private function hitungEstimasi()
    {
        if (! $this->pesanan || $this->statusPembayaran !== 'Sudah Dibayar') {
            return null;
        }

        $waktuPesan = Carbon::parse($this->pesanan->tanggalPesanan);

        switch ($this->statusPesanan) {

            /**STATUS: DIPROSES*/
            case 'Diproses':
                // Hitung antrean sebelumnya
                $jumlahAntreanSebelumnya = Pesanan::whereDate(
                        'tanggalPesanan',
                        $waktuPesan->toDateString()
                    )
                    ->where('noAntrean', '<', $this->noAntrean)
                    ->where('statusPesanan', 'Diproses')
                    ->count();

                // 1 pesanan = 10 menit
                $totalMenit = ($jumlahAntreanSebelumnya + 1) * 10;

                $estimasi = $waktuPesan->copy()->addMinutes($totalMenit);
                $label = 'Estimasi selesai pembuatan';
                break;

            /*STATUS: SEDANG DIKIRIM*/
            case 'Sedang Dikirim':
                if (! $this->pesanan->lokasiGPS) {
                    return 'Pesanan sedang dikirim';
                }

                [$lat, $lng] = array_map(
                    'floatval',
                    explode(',', $this->pesanan->lokasiGPS)
                );

                $jarakKm = $this->hitungJarakHaversine(
                    $this->tokoLat,
                    $this->tokoLng,
                    $lat,
                    $lng
                );

                // Konversi jarak ke menit
                $waktuKirim = ceil($jarakKm * 5);

                $estimasi = $waktuPesan->copy()->addMinutes($waktuKirim);
                $label = 'Estimasi pesanan tiba';
                break;

            /*STATUS: SUDAH SAMPAI*/
            case 'Sudah Sampai':
                return 'Pesanan sudah tiba di tujuan';

            default:
                return null;
        }

        if ($estimasi->isPast()) {
            return "{$label}: sebentar lagi";
        }

        return "{$label}: sekitar pukul " . $estimasi->format('H:i');
    }

    /**
     * Rumus (KM)
     */
    private function hitungJarakHaversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
             cos(deg2rad($lat1)) *
             cos(deg2rad($lat2)) *
             sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function render()
    {
        return view('livewire.pelanggan.queue', [
            'statusPembayaran' => $this->statusPembayaran,
            'statusPesanan' => $this->statusPesanan,
            'noAntrean' => $this->noAntrean,
            'metodePengantaran' => $this->metodePengantaran,
            'estimasiWaktu' => $this->estimasiWaktu,
        ]);
    }
}
