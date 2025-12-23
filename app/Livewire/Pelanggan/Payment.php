<?php

namespace App\Livewire\Pelanggan;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Session;
use App\Models\{Pesanan, Pembayaran, ItemPesanan, Stok, Pelanggan};
use Livewire\Attributes\Layout;

#[Layout('pelanggan')]
class Payment extends Component
{
    use WithFileUploads;

    public $metodePembayaran = null;
    public $buktiPembayaran;
    public $total = 0;
    public $biayaKirim = 0;
    public $alamatPelanggan = null;

    public function mount()
    {
        // Ambil data dari session
        $this->total = Session::get('total_pembayaran', 0);
        $this->alamatPelanggan = Session::get('alamat_pelanggan');
        $this->biayaKirim = Session::get('biaya_kirim', 0);
    }

    public function pilihMetode($metode)
    {
        $this->metodePembayaran = $metode;
    }

    public function konfirmasiPembayaran()
    {
        $cart = Session::get('cart', []);
        $idPelanggan = Session::get('idPelanggan');
        $lokasiGPS = Session::get('lokasiGPS');
        $namaPelanggan = Session::get('namaPelanggan');
        $noHP = Session::get('noHP');
        $metodePemesanan = Session::get('metodePemesanan', 'ditempat');

        if (empty($cart) || !$idPelanggan) {
            session()->flash('error', 'Data pelanggan tidak ditemukan.');
            return;
        }

        $pelanggan = Pelanggan::find($idPelanggan);
        if ($pelanggan) {
            $pelanggan->update([
                'namaPelanggan' => $namaPelanggan ?? $pelanggan->namaPelanggan,
                'noHP' => $noHP ?? $pelanggan->noHP,
            ]);
        }

        // Validasi file upload hanya jika pakai QRIS
        if ($this->metodePembayaran === 'QRIS') {
            $this->validate([
                'buktiPembayaran' => 'required|image|max:2048', // 2MB max
            ]);
        }

        // Buat pesanan baru
        $pesanan = Pesanan::create([
            'idPelanggan' => $idPelanggan,
            'tanggalPesanan' => now(),
            'statusPesanan' => 'Diproses',
            'totalPembayaran' => $this->total,
            'lokasiGPS' => $lokasiGPS,
            'sumberPesanan' => 'Pelanggan',
        ]);

        // Simpan item pesanan
        foreach ($cart as $item) {
            ItemPesanan::create([
                'idPesanan' => $pesanan->idPesanan,
                'idMenu' => $item['idMenu'],
                'idTopping' => $item['idTopping'] ?? null,
                'jumlahPesanan' => $item['jumlah'],
                'catatanPesanan' => $item['catatan'] ?? null,
            ]);

            // Kurangi stok
            $stok = Stok::where('idMenu', $item['idMenu'])->first();
            if ($stok) {
                $stok->jumlahStok = max(0, $stok->jumlahStok - $item['jumlah']);
                $stok->save();
            }
        }

        // Simpan pembayaran
        Pembayaran::create([
            'idPesanan' => $pesanan->idPesanan,
            'metodePembayaran' => $this->metodePembayaran,
            'statusPembayaran' => 'Belum Dibayar',
            'buktiPembayaran' => $this->buktiPembayaran
                ? $this->buktiPembayaran->store('bukti', 'public')
                : null,
        ]);

        // Bersihkan session
        Session::forget([
            'cart',
            'idPelanggan',
            'alamat_pelanggan',
            'lokasiGPS',
            'namaPelanggan',
            'noHP',
            'total_pembayaran',
            'metodePemesanan'
        ]);

        return redirect()->route('pelanggan.queue', ['id' => $pesanan->idPesanan]);
    }

    public function kembaliKeKeranjang()
    {
        return redirect()->route('pelanggan.cart');
    }

    public function render()
    {
        return view('livewire.pelanggan.payment');
    }
}
