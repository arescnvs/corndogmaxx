<?php

namespace App\Livewire\Pelanggan;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use App\Models\Pelanggan;
use Livewire\Attributes\Layout;

#[Layout('pelanggan')]
class Pemesanan extends Component
{
    public $selectedMethod = null;

    public function pilihMetode($method)
    {
        $this->selectedMethod = $method;
    }

    public function lanjutDariMetode()
    {
        if (!$this->selectedMethod) {
            session()->flash('error', 'Pilih salah satu metode pemesanan terlebih dahulu.');
            return;
        }

        if ($this->selectedMethod === 'kirim') {
            // Jika kirim → ke halaman address
            return redirect()->route('pelanggan.address');
        }

        if ($this->selectedMethod === 'ditempat') {
            // Jika di tempat → buat pelanggan guest otomatis
            $pelanggan = Pelanggan::create([
                'namaPelanggan' => 'Guest-' . rand(1000, 9999),
                'alamat' => '-',
                'noHP' => '-',
            ]);

            // Simpan ke session
            session([
                'idPelanggan' => $pelanggan->idPelanggan,
                'namaPelanggan' => $pelanggan->namaPelanggan,
                'alamat_pelanggan' => null,
                'lokasiGPS' => null,
                'metodePemesanan' => 'ditempat',
            ]);

            return redirect()->route('pelanggan.menu');
        }
    }

    public function render()
    {
        return view('livewire.pelanggan.pemesanan');
    }
}
