<?php

namespace App\Livewire\Kasir;

use App\Models\Stok;
use App\Models\Menu;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app.sidebar')]
class LihatStok extends Component
{
    public $stok;
    public $showModal = false;
    public $selectedStok = null;
    public $jumlahBaru = null;
    public $isInputMode = false; // mode input atau edit

    public function mount()
    {
        $this->sinkronMenuDenganStok();
        $this->loadStok();
    }

    /**
     * Sinkronkan semua menu agar punya stok di tabel stok.
     * Menu baru otomatis dapat stok 0.
     */
    private function sinkronMenuDenganStok()
    {
        $idKasir = Auth::user()->idKasir ?? null;
        if (!$idKasir) return;

        $semuaMenu = Menu::all();

        foreach ($semuaMenu as $menu) {
            $stokAda = Stok::where('idMenu', $menu->idMenu)
                ->where('idKasir', $idKasir)
                ->exists();

            if (!$stokAda) {
                Stok::create([
                    'idMenu' => $menu->idMenu,
                    'idKasir' => $idKasir,
                    'jumlahStok' => 0, // default stok baru = 0
                ]);
            }
        }
    }

    public function loadStok()
    {
        $this->stok = Stok::with('menu')
            ->where('idKasir', Auth::user()->idKasir)
            ->orderByDesc('idStok')
            ->get();
    }

    public function openModal($idStok, $isInput = false)
    {
        $this->selectedStok = Stok::with('menu')->find($idStok);
        $this->jumlahBaru = $isInput ? null : $this->selectedStok->jumlahStok;
        $this->isInputMode = $isInput;
        $this->showModal = true;
    }

    public function saveStok()
    {
        $this->validate([
            'jumlahBaru' => 'required|integer|min:0',
        ]);

        $this->selectedStok->update([
            'jumlahStok' => $this->jumlahBaru,
        ]);

        $this->showModal = false;
        $this->isInputMode = false;
        $this->loadStok();

        $message = $this->isInputMode
            ? 'Stok baru berhasil ditambahkan!'
            : 'Stok berhasil diperbarui!';

        session()->flash('message', $message);
    }

    public function render()
    {
        return view('livewire.kasir.lihat-stok');
    }
}
