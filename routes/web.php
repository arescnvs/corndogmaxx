<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Livewire\Settings\{Appearance, Password, Profile, TwoFactor};
use App\Livewire\Kasir\{Dashboard, LihatStok, DaftarPesanan, InputPesanan};
use App\Livewire\Pelanggan\{Pemesanan, Address, MenuPelanggan, Customization, Cart, Payment, Queue};
use App\Livewire\Auth\{Login, Register};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| ROUTE PELANGGAN (TANPA LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/', Pemesanan::class)->name('pemesanan');
Route::get('/', Pemesanan::class)->name('home');
Route::get('/pelanggan/address', Address::class)->name('pelanggan.address');
Route::get('/pelanggan/menu', MenuPelanggan::class)->name('pelanggan.menu');
Route::get('/pelanggan/customization', Customization::class)->name('pelanggan.customization');
Route::get('/pelanggan/cart', Cart::class)->name('pelanggan.cart');
Route::get('/pelanggan/payment', Payment::class)->name('pelanggan.payment');
Route::get('/pelanggan/queue/{id}', Queue::class)->name('pelanggan.queue');

/*
|--------------------------------------------------------------------------
| ROUTE LOGIN & REGISTER (FORTIFY)
|--------------------------------------------------------------------------
*/
Route::get('/login', Login::class)->name('login');
Route::get('/register', fn() => abort(404));
Route::get('/unayaha', Register::class)->name('secret.register');

/*
|--------------------------------------------------------------------------
| ROUTE SETTINGS (USER LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

/*
|--------------------------------------------------------------------------
| ROUTE KASIR (SETELAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('ares', Dashboard::class)->name('kasir.dashboard');
    Route::get('/kasir/lihat-stok', LihatStok::class)->name('kasir.lihat-stok');
    Route::get('/kasir/daftar-pesanan', DaftarPesanan::class)->name('kasir.daftar-pesanan');
    Route::get('/kasir/input-pesanan', InputPesanan::class)->name('kasir.input-pesanan');
});

Route::get('/register', function () {abort(404);});

/*
|--------------------------------------------------------------------------
| ROUTE LOGOUT CUSTOM (REDIRECT KE /login)
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (FORTIFY DEFAULT)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
