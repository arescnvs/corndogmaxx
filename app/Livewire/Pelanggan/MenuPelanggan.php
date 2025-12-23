<?php

namespace App\Livewire\Pelanggan;

use Livewire\Component;
use App\Models\Menu as MenuModel;
use Livewire\Attributes\Layout;

#[Layout('pelanggan')]
class MenuPelanggan extends Component
{
    public $menuList = [];

    public function mount()
    {
        $this->menuList = MenuModel::all();
    }

    public function pilihMenu($idMenu)
    {
        // simpan id menu ke session sementara untuk customization
        session(['selected_menu' => $idMenu]);

        return redirect()->route('pelanggan.customization');
    }

    public function lihatKeranjang()
    {
        return redirect()->route('pelanggan.cart');
    }

    public function render()
    {
        return view('livewire.pelanggan.menu');
    }
}