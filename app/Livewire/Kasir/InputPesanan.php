<?php

namespace App\Livewire\Kasir;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Topping;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\ItemPesanan;
use App\Models\Pembayaran;
use App\Models\Stok;
use Illuminate\Support\Facades\DB;

class InputPesanan extends Component
{
    public $namaPelanggan;
    public $items = [];
    public $menuList = [];
    public $toppingList = [];
    public $subtotal = 0;
    public $metodePembayaran;
    public $catatan;

    protected $listeners = ['stokError' => 'showStokError'];

    public function mount()
    {
        // ✅ Semua menu tetap tampil, walau stoknya habis
        $this->menuList = Menu::all();
        $this->toppingList = Topping::all();

        $this->items = [[
            'idMenu' => '',
            'jumlah' => 1,
            'idTopping' => '',
            'harga' => 0,
        ]];

        $this->hitungSubtotal();
    }

    public function render()
    {
        return view('livewire.kasir.input-pesanan');
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'items')) {
            $this->hitungSubtotal();
        }
    }

    private function hitungSubtotal()
    {
        $this->subtotal = 0;

        foreach ($this->items as $index => &$item) {
            if (!empty($item['idMenu'])) {
                $menu = Menu::find($item['idMenu']);
                if ($menu) {
                    $stok = Stok::where('idMenu', $menu->idMenu)->value('jumlahStok') ?? 0;

                    // batasi jumlah maksimal sesuai stok
                    if (($item['jumlah'] ?? 1) > $stok) {
                        $item['jumlah'] = $stok;
                        $this->dispatch('stokError', message: "Stok {$menu->namaMenu} hanya tersisa {$stok}!");
                    }

                    $jumlah = $item['jumlah'] ?? 0;
                    $item['harga'] = $menu->hargaProduk * $jumlah;
                    $this->subtotal += $item['harga'];
                } else {
                    $item['harga'] = 0;
                }
            } else {
                $item['harga'] = 0;
            }
        }

        $this->items = collect($this->items)->values()->toArray();
    }

    public function tambahItem()
    {
        $this->items[] = [
            'idMenu' => '',
            'jumlah' => 1,
            'idTopping' => '',
            'harga' => 0,
        ];

        $this->hitungSubtotal();
    }

    public function hapusItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->hitungSubtotal();
    }

    public function simpanPesanan()
    {
        $this->validate([
            'namaPelanggan' => 'required|string|max:100',
            'metodePembayaran' => 'required',
        ]);

        // 🛑 Cegah pesanan jika stok tidak cukup
        foreach ($this->items as $item) {
            if (!empty($item['idMenu'])) {
                $stok = Stok::where('idMenu', $item['idMenu'])->first();
                $menu = Menu::find($item['idMenu']);

                if (!$stok || $stok->jumlahStok <= 0) {
                    $this->dispatchBrowserEvent('stokError', [
                        'message' => "Stok {$menu->namaMenu} habis, tidak bisa dipesan."
                    ]);
                    return;
                }

                if ($stok->jumlahStok < ($item['jumlah'] ?? 1)) {
                    $this->dispatchBrowserEvent('stokError', [
                        'message' => "Stok {$menu->namaMenu} hanya tersisa {$stok->jumlahStok}."
                    ]);
                    return;
                }
            }
        }

        DB::transaction(function () {
            $pelanggan = Pelanggan::create([
                'namaPelanggan' => $this->namaPelanggan,
                'noHP' => '-',
                'alamat' => '-',
            ]);

            $noAntrean = (Pesanan::max('noAntrean') ?? 0) + 1;

            $pesanan = Pesanan::create([
                'idPelanggan' => $pelanggan->idPelanggan,
                'idKasir' => auth()->id(),
                'tanggalPesanan' => now(),
                'statusPesanan' => 'Diproses',
                'sumberPesanan' => 'Kasir',
                'noAntrean' => $noAntrean,
                'totalPembayaran' => $this->subtotal,
            ]);

            foreach ($this->items as $item) {
                if (!empty($item['idMenu'])) {
                    $menu = Menu::find($item['idMenu']);
                    $stok = Stok::where('idMenu', $menu->idMenu)->first();
                    $jumlah = $item['jumlah'] ?? 1;

                    if ($stok) {
                        $stok->jumlahStok = max(0, $stok->jumlahStok - $jumlah);
                        $stok->save();
                    }

                    ItemPesanan::create([
                        'idPesanan' => $pesanan->idPesanan,
                        'idMenu' => $menu->idMenu,
                        'idTopping' => $item['idTopping'] ?: null,
                        'jumlahPesanan' => $jumlah,
                        'catatanPesanan' => $this->catatan ?? null,
                    ]);
                }
            }

            Pembayaran::create([
                'idPesanan' => $pesanan->idPesanan,
                'metodePembayaran' => $this->metodePembayaran,
                'statusPembayaran' => 'Belum Dibayar',
            ]);
        });

        $this->reset(['namaPelanggan', 'items', 'subtotal', 'metodePembayaran', 'catatan']);
        $this->items = [['idMenu' => '', 'jumlah' => 1, 'idTopping' => '', 'harga' => 0]];
        $this->hitungSubtotal();

        session()->flash('success', '✅ Pesanan berhasil disimpan dan stok diperbarui!');
    }
}
