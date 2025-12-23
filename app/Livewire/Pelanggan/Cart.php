<?php

namespace App\Livewire\Pelanggan;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use App\Models\Stok;
use App\Models\Menu;

#[Layout('pelanggan')]
class Cart extends Component
{
    public $cart = [];
    public $namaPelanggan = '';
    public $noHP = '';
    public $subtotal = 0;
    public $biayaKirim = 0;
    public $total = 0;
    public $alamatPelanggan = null;
    public $isDelivery = false;
    public $jarakKeToko = 0;

    public function mount()
    {
        $this->cart = Session::get('cart', []);

        // Validasi stok setiap kali cart dibuka
        $this->cart = collect($this->cart)->map(function ($item) {
            $stok = Stok::where('idMenu', $item['idMenu'])->value('jumlahStok') ?? 0;
            $menu = Menu::find($item['idMenu']);

            if ($stok <= 0) {
                return null; // hapus item kalau stok habis
            }

            if ($item['jumlah'] > $stok) {
                $item['jumlah'] = $stok;
                $item['total'] = ($menu->hargaProduk ?? 0) * $stok;
            }

            return $item;
        })->filter()->values()->toArray();

        Session::put('cart', $this->cart);

        // Info pengantaran
        $this->alamatPelanggan = Session::get('alamat_pelanggan');
        $this->isDelivery = Session::has('lokasiGPS');
        $this->jarakKeToko = Session::get('jarak_km', 0);
        $this->biayaKirim = Session::get('biaya_kirim', 0);

        $this->hitungTotal();

        if ($this->isDelivery && Session::has('namaPelanggan')) {
            $this->namaPelanggan = Session::get('namaPelanggan');
        }
    }

    public function hitungTotal()
    {
        $this->subtotal = collect($this->cart)->sum('total');
        $this->biayaKirim = $this->isDelivery ? Session::get('biaya_kirim', 0) : 0;
        $this->total = $this->subtotal + $this->biayaKirim;
        Session::put('total_pembayaran', $this->total);
    }

    public function hapusItem($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        Session::put('cart', $this->cart);
        $this->hitungTotal();
    }

    public function editItem($index)
    {
        if (!isset($this->cart[$index])) return;

        Session::put('edit_item', $this->cart[$index]);
        Session::put('edit_index', $index);
        Session::put('selected_menu', $this->cart[$index]['idMenu']);

        return redirect()->route('pelanggan.customization');
    }

    public function lanjutPembayaran()
    {
        $rules = $this->isDelivery
            ? ['noHP' => 'required|string|max:20']
            : ['namaPelanggan' => 'required|string|max:100'];

        $this->validate($rules, [
            'namaPelanggan.required' => 'Nama wajib diisi.',
            'noHP.required' => 'Nomor HP wajib diisi untuk pengantaran.',
        ]);

        Session::put('namaPelanggan', $this->namaPelanggan);
        Session::put('noHP', $this->noHP);
        Session::put('total_pembayaran', $this->total);
        Session::put('biaya_kirim', $this->biayaKirim ?? 0);
        Session::put('jarak_km', $this->jarakKeToko ?? 0);

        return redirect()->route('pelanggan.payment');
    }

    public function kembaliKeMenu()
    {
        return redirect()->route('pelanggan.menu');
    }

    public function render()
    {
        return view('livewire.pelanggan.cart', [
            'cart' => $this->cart,
            'subtotal' => $this->subtotal,
            'biayaKirim' => $this->biayaKirim,
            'total' => $this->total,
            'alamatPelanggan' => $this->alamatPelanggan,
            'isDelivery' => $this->isDelivery,
            'jarakKeToko' => $this->jarakKeToko,
        ]);
    }
}
