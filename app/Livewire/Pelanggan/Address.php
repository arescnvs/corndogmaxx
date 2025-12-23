<?php

namespace App\Livewire\Pelanggan;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Session;

#[Layout('pelanggan')]
class Address extends Component
{
    public $namaPelanggan = '';
    public $alamat = '';
    public $lokasiGPS = null;
    public $jarakKeToko = null;
    public $biayaKirim = 0;

    private $lokasiToko = ['lat' => -6.295219745529388, 'long' => 106.73510450931035];  

    public function setLokasi($lat, $long)
    {
        $this->lokasiGPS = "{$lat},{$long}";

        $jarak = $this->hitungJarak(
            $this->lokasiToko['lat'],
            $this->lokasiToko['long'],
            $lat,
            $long
        );

        $this->jarakKeToko = round($jarak, 2);
        $this->biayaKirim = $this->hitungBiayaKirim($this->jarakKeToko);

        if ($this->jarakKeToko > 20) {
            $this->addError('lokasi', 'Lokasi Anda di luar jangkauan pengantaran (>20 km).');
        } else {
            $this->resetErrorBag('lokasi');
        }

        session([
            'lokasiGPS' => $this->lokasiGPS,
            'jarak_km' => $this->jarakKeToko,
            'biaya_kirim' => $this->biayaKirim,
        ]);
    }

    public function konfirmasiAlamat()
    {
        $this->validate([
            'namaPelanggan' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
        ]);

        if (!$this->lokasiGPS) {
            $this->addError('lokasi', 'Aktifkan izin lokasi untuk melanjutkan.');
            return;
        }

        if ($this->jarakKeToko !== null && $this->jarakKeToko > 20) {
            $this->addError('lokasi', 'Lokasi Anda di luar jangkauan pengantaran.');
            return;
        }

        // Buat pelanggan baru otomatis (guest)
        $pelanggan = Pelanggan::create([
            'namaPelanggan' => $this->namaPelanggan,
            'alamat' => $this->alamat,
            'noHP' => '-',
        ]);

        // Simpan ke session
        session([
            'idPelanggan' => $pelanggan->idPelanggan,
            'namaPelanggan' => $this->namaPelanggan,
            'alamat_pelanggan' => $this->alamat,
            'lokasiGPS' => $this->lokasiGPS,
            'jarak_km' => $this->jarakKeToko,
            'biaya_kirim' => $this->biayaKirim,
        ]);

        return redirect()->route('pelanggan.menu');
    }

    public function kembali()
    {
        return redirect()->route('home');
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    private function hitungBiayaKirim($jarak)
    {
        if ($jarak <= 1) {
            return 0;
        }

        if ($jarak > 20) {
            return 0; // tidak dihitung karena di luar jangkauan
        }

        return ceil($jarak - 1) * 1000;
    }

    public function render()
    {
        return view('livewire.pelanggan.address', [
            'jarakKeToko' => $this->jarakKeToko,
            'biayaKirim' => $this->biayaKirim,
        ]);
    }
}
