<?php

namespace App\Livewire\Pelanggan;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Topping;
use App\Models\Stok;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;

#[Layout('pelanggan')]
class Customization extends Component
{
    public $menu;
    public $toppingList = [];
    public $selectedTopping = null;
    public $jumlah = 1;
    public $catatan = '';
    public $stokTersedia = 0;

    // Tambahan untuk fitur edit
    public $isEditing = false;
    public $editIndex = null;

    public function mount()
    {
        $idMenu = session('selected_menu');
        $editItem = session('edit_item');
        $this->editIndex = session('edit_index');

        if (!$idMenu) {
            return redirect()->route('pelanggan.menu');
        }

        $this->menu = Menu::find($idMenu);
        $this->toppingList = Topping::all();

        // Ambil stok dari tabel stok
        $this->stokTersedia = Stok::where('idMenu', $idMenu)->value('jumlahStok') ?? 0;

        // Kalau stok 0, jumlah langsung jadi 0
        if ($this->stokTersedia <= 0) {
            $this->jumlah = 0;
        }

        // Jika sedang mengedit item lama
        if ($editItem) {
            $this->isEditing = true;
            $this->selectedTopping = $editItem['idTopping'] ?? null;
            $this->jumlah = min($editItem['jumlah'] ?? 1, $this->stokTersedia);
            $this->catatan = $editItem['catatan'] ?? '';
        }
    }

    public function ubahJumlah($delta)
    {
        if ($this->stokTersedia <= 0) return;

        $newJumlah = $this->jumlah + $delta;

        // Pastikan tidak lebih dari stok dan tidak kurang dari 1
        if ($newJumlah < 1) {
            $this->jumlah = 1;
        } elseif ($newJumlah > $this->stokTersedia) {
            $this->dispatch('stokLimit', message: "Stok hanya tersisa {$this->stokTersedia}!");
            $this->jumlah = $this->stokTersedia;
        } else {
            $this->jumlah = $newJumlah;
        }
    }

    public function tambahKeKeranjang()
    {
        if (!$this->menu) return;

        if ($this->stokTersedia <= 0) {
            $this->dispatch('stokLimit', message: "Menu ini sedang kehabisan stok!");
            return;
        }

        $hargaTopping = 0;
        $topping = null;

        if (!$this->selectedTopping) {
            $this->selectedTopping = 7;
        }
        
        $toppingObj = Topping::find($this->selectedTopping);
        if ($toppingObj) {
            $hargaTopping = $toppingObj->hargaTopping ?? 0;
            $topping = $toppingObj->namaTopping ?? null;
        }

        $total = ($this->menu->hargaProduk + $hargaTopping) * $this->jumlah;
        $cart = Session::get('cart', []);

        // Jika sedang mengedit item lama → ganti, bukan tambah
        if ($this->isEditing && $this->editIndex !== null && isset($cart[$this->editIndex])) {
            $cart[$this->editIndex] = [
                'idMenu' => $this->menu->idMenu,
                'namaMenu' => $this->menu->namaMenu,
                'jumlah' => $this->jumlah,
                'idTopping' => $this->selectedTopping ?? null,
                'topping' => $topping,
                'catatan' => $this->catatan,
                'total' => $total,
            ];
        } else {
            // Tambah item baru
            $cart[] = [
                'idMenu' => $this->menu->idMenu,
                'namaMenu' => $this->menu->namaMenu,
                'jumlah' => $this->jumlah,
                'idTopping' => $this->selectedTopping ?? null,
                'topping' => $topping,
                'catatan' => $this->catatan,
                'total' => $total,
            ];
        }

        Session::put('cart', $cart);

        // Bersihkan session editing
        Session::forget(['selected_menu', 'edit_item', 'edit_index']);

        return redirect()
            ->route('pelanggan.menu')
            ->with('success', $this->isEditing ? 'Item berhasil diperbarui!' : 'Item berhasil ditambahkan ke keranjang!');
    }

    public function kembaliKeMenu()
    {
        Session::forget(['selected_menu', 'edit_item', 'edit_index']);
        return redirect()->route('pelanggan.menu');
    }

    public function render()
    {
        return view('livewire.pelanggan.customization');
    }
}
