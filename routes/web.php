<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonasiAdminController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KeluhanAdminController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PesananAdminController;
use App\Http\Controllers\Admin\ProdukAdminController;
use App\Http\Controllers\Admin\ReturAdminController;
use App\Http\Controllers\Admin\SlotDonasiController;
use App\Http\Controllers\Admin\SlotTtcController;
use App\Http\Controllers\Admin\TokoAdminController;
use App\Http\Controllers\Admin\TtcAdminController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LupaSandiController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\LacakController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PoinController;
use App\Http\Controllers\PreferensiController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\TtcController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\VtoController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman umum
|--------------------------------------------------------------------------
*/
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::post('/tema', [PreferensiController::class, 'tema'])->name('tema.ubah');

Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk-baru', [ProdukController::class, 'baru'])->name('produk.baru');
Route::get('/produk/{product}', [ProdukController::class, 'show'])->name('produk.show');
Route::get('/produk/{product}/stok-toko', [ProdukController::class, 'stokToko'])->name('produk.stok');

Route::get('/toko', [TokoController::class, 'index'])->name('toko.index');
Route::get('/toko/{store}', [TokoController::class, 'show'])->name('toko.show');
Route::get('/toko/{store}/qr-kasir', [TokoController::class, 'qrKasir'])->name('toko.qr');

Route::get('/lacak-pesanan', [LacakController::class, 'index'])->name('lacak.index');
Route::post('/lacak-pesanan', [LacakController::class, 'cari'])->name('lacak.cari');

Route::get('/bantuan', [BantuanController::class, 'index'])->name('bantuan.index');
Route::get('/bantuan/{halaman}', [BantuanController::class, 'show'])->name('bantuan.show');

// Keranjang boleh dipakai tamu, isinya disimpan memakai session id
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
Route::post('/keranjang/pilih-semua', [KeranjangController::class, 'pilihSemua'])->name('keranjang.pilih-semua');
Route::put('/keranjang/{item}', [KeranjangController::class, 'update'])->name('keranjang.update');
Route::post('/keranjang/{item}/pilih', [KeranjangController::class, 'pilih'])->name('keranjang.pilih');
Route::delete('/keranjang/{item}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');

// Tap Try and Collect dan donasi: halaman dan daftar slot boleh dilihat tamu
Route::get('/tap-try-collect', [TtcController::class, 'index'])->name('ttc.index');
Route::get('/tap-try-collect/slot', [TtcController::class, 'slot'])->name('ttc.slot');
Route::get('/donasi', [DonasiController::class, 'index'])->name('donasi.index');
Route::get('/donasi/slot', [DonasiController::class, 'slot'])->name('donasi.slot');

// Virtual Try On
Route::get('/virtual-try-on', [VtoController::class, 'index'])->name('vto.index');
Route::post('/virtual-try-on', [VtoController::class, 'store'])->name('vto.store');
Route::get('/virtual-try-on/{virtualTryOn}', [VtoController::class, 'show'])->name('vto.show');
Route::post('/virtual-try-on/{virtualTryOn}/proses', [VtoController::class, 'proses'])->name('vto.proses');
Route::delete('/virtual-try-on/{virtualTryOn}', [VtoController::class, 'destroy'])->name('vto.destroy');

// Notifikasi pembayaran dari server Midtrans (tanpa CSRF, diperiksa signature)
Route::post('/payment/notification', [PembayaranController::class, 'notifikasi'])->name('pembayaran.notifikasi');

// Halaman verifikasi staf toko (dibuka staf setelah memindai QR pelanggan)
Route::get('/staf/verifikasi/{jenis}/{kode}', [StafController::class, 'form'])
    ->whereIn('jenis', ['donasi', 'ttc', 'retur'])->name('staf.verifikasi');
Route::post('/staf/verifikasi/{jenis}/{kode}', [StafController::class, 'simpan'])
    ->whereIn('jenis', ['donasi', 'ttc', 'retur'])->name('staf.verifikasi.simpan');

/*
|--------------------------------------------------------------------------
| Masuk dan daftar
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'formMasuk'])->name('login');
    Route::post('/masuk', [AuthController::class, 'masuk'])->middleware('throttle:8,1')->name('login.proses');
    Route::get('/daftar', [AuthController::class, 'formDaftar'])->name('register');
    Route::post('/daftar', [AuthController::class, 'daftar'])->middleware('throttle:6,1')->name('register.store');

    Route::get('/lupa-kata-sandi', [LupaSandiController::class, 'formPermintaan'])->name('password.request');
    Route::post('/lupa-kata-sandi', [LupaSandiController::class, 'kirimTautan'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/atur-kata-sandi/{token}', [LupaSandiController::class, 'formAtur'])->name('password.reset');
    Route::post('/atur-kata-sandi', [LupaSandiController::class, 'atur'])->name('password.update');
});

Route::post('/keluar', [AuthController::class, 'keluar'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Halaman pelanggan yang sudah masuk
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Akun
    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::get('/akun/profil', [AkunController::class, 'formProfil'])->name('profil.edit');
    Route::put('/akun/profil', [AkunController::class, 'simpanProfil'])->name('profil.update');
    Route::put('/akun/kata-sandi', [AkunController::class, 'gantiSandi'])->name('sandi.update');

    // Alamat pengiriman (CRUD)
    Route::get('/akun/alamat', [AlamatController::class, 'index'])->name('alamat.index');
    Route::get('/akun/alamat/tambah', [AlamatController::class, 'create'])->name('alamat.create');
    Route::post('/akun/alamat', [AlamatController::class, 'store'])->name('alamat.store');
    Route::get('/akun/alamat/{address}/ubah', [AlamatController::class, 'edit'])->name('alamat.edit');
    Route::put('/akun/alamat/{address}', [AlamatController::class, 'update'])->name('alamat.update');
    Route::put('/akun/alamat/{address}/utama', [AlamatController::class, 'jadikanUtama'])->name('alamat.utama');
    Route::delete('/akun/alamat/{address}', [AlamatController::class, 'destroy'])->name('alamat.destroy');

    // Poin, notifikasi, keluhan
    Route::get('/akun/poin', [PoinController::class, 'index'])->name('poin.index');
    Route::get('/akun/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/akun/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.baca-semua');
    Route::get('/akun/notifikasi/{notification}', [NotifikasiController::class, 'buka'])->name('notifikasi.buka');
    Route::get('/akun/keluhan', [KeluhanController::class, 'index'])->name('keluhan.index');
    Route::post('/akun/keluhan', [KeluhanController::class, 'store'])->name('keluhan.store');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Checkout dan pembayaran
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/voucher', [CheckoutController::class, 'voucher'])->name('checkout.voucher');
    Route::delete('/checkout/voucher', [CheckoutController::class, 'hapusVoucher'])->name('checkout.voucher.hapus');

    Route::get('/pembayaran/{payment}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran/{payment}/status', [PembayaranController::class, 'status'])->name('pembayaran.status');
    Route::get('/pembayaran/{payment}/selesai', [PembayaranController::class, 'selesai'])->name('pembayaran.selesai');

    // Pesanan
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{order}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::put('/pesanan/{order}/batal', [PesananController::class, 'batal'])->name('pesanan.batal');
    Route::put('/pesanan/{order}/terima', [PesananController::class, 'terima'])->name('pesanan.terima');
    Route::post('/pesanan/{order}/beli-lagi', [PesananController::class, 'beliLagi'])->name('pesanan.beli-lagi');
    Route::post('/pesanan/{order}/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');

    // Retur
    Route::get('/retur', [ReturController::class, 'index'])->name('retur.index');
    Route::post('/retur/cari-struk', [ReturController::class, 'cariStruk'])->name('retur.cari-struk');
    Route::get('/retur/ajukan/{order}', [ReturController::class, 'create'])->name('retur.create');
    Route::post('/retur/ajukan/{order}', [ReturController::class, 'store'])->name('retur.store');
    Route::get('/retur/{returnRequest}', [ReturController::class, 'show'])->name('retur.show');
    Route::put('/retur/{returnRequest}/batal', [ReturController::class, 'batal'])->name('retur.batal');

    // Tap Try and Collect
    Route::post('/tap-try-collect', [TtcController::class, 'store'])->name('ttc.store');
    Route::get('/akun/tap-try-collect', [TtcController::class, 'riwayat'])->name('ttc.riwayat');
    Route::get('/akun/tap-try-collect/{tapTryCollect}', [TtcController::class, 'show'])->name('ttc.show');
    Route::put('/akun/tap-try-collect/{tapTryCollect}/batal', [TtcController::class, 'batal'])->name('ttc.batal');

    // Donasi pakaian
    Route::post('/donasi', [DonasiController::class, 'store'])->name('donasi.store');
    Route::get('/akun/donasi', [DonasiController::class, 'riwayat'])->name('donasi.riwayat');
    Route::get('/akun/donasi/{donation}', [DonasiController::class, 'show'])->name('donasi.show');
    Route::put('/akun/donasi/{donation}/check-in', [DonasiController::class, 'checkIn'])->name('donasi.check-in');
    Route::put('/akun/donasi/{donation}/batal', [DonasiController::class, 'batal'])->name('donasi.batal');
});

/*
|--------------------------------------------------------------------------
| Dashboard admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Produk (CRUD lengkap)
    Route::get('/produk', [ProdukAdminController::class, 'index'])->name('produk.index');
    Route::get('/produk/tambah', [ProdukAdminController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukAdminController::class, 'store'])->name('produk.store');
    Route::get('/produk/{product}', [ProdukAdminController::class, 'show'])->name('produk.show');
    Route::get('/produk/{product}/ubah', [ProdukAdminController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{product}', [ProdukAdminController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{product}', [ProdukAdminController::class, 'destroy'])->name('produk.destroy');
    Route::put('/produk/{product}/stok', [ProdukAdminController::class, 'ubahStok'])->name('produk.stok');

    // Kategori (CRUD)
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{category}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{category}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Toko (CRUD)
    Route::get('/toko', [TokoAdminController::class, 'index'])->name('toko.index');
    Route::get('/toko/tambah', [TokoAdminController::class, 'create'])->name('toko.create');
    Route::post('/toko', [TokoAdminController::class, 'store'])->name('toko.store');
    Route::get('/toko/{store}/ubah', [TokoAdminController::class, 'edit'])->name('toko.edit');
    Route::put('/toko/{store}', [TokoAdminController::class, 'update'])->name('toko.update');
    Route::delete('/toko/{store}', [TokoAdminController::class, 'destroy'])->name('toko.destroy');

    // Slot donasi (CRUD, kuota terpisah)
    Route::get('/slot-donasi', [SlotDonasiController::class, 'index'])->name('slot-donasi.index');
    Route::get('/slot-donasi/tambah', [SlotDonasiController::class, 'create'])->name('slot-donasi.create');
    Route::post('/slot-donasi', [SlotDonasiController::class, 'store'])->name('slot-donasi.store');
    Route::get('/slot-donasi/{donationSlot}/ubah', [SlotDonasiController::class, 'edit'])->name('slot-donasi.edit');
    Route::put('/slot-donasi/{donationSlot}', [SlotDonasiController::class, 'update'])->name('slot-donasi.update');
    Route::delete('/slot-donasi/{donationSlot}', [SlotDonasiController::class, 'destroy'])->name('slot-donasi.destroy');

    // Slot Tap Try and Collect (CRUD, kuota terpisah)
    Route::get('/slot-ttc', [SlotTtcController::class, 'index'])->name('slot-ttc.index');
    Route::get('/slot-ttc/tambah', [SlotTtcController::class, 'create'])->name('slot-ttc.create');
    Route::post('/slot-ttc', [SlotTtcController::class, 'store'])->name('slot-ttc.store');
    Route::get('/slot-ttc/{tapTryCollectSlot}/ubah', [SlotTtcController::class, 'edit'])->name('slot-ttc.edit');
    Route::put('/slot-ttc/{tapTryCollectSlot}', [SlotTtcController::class, 'update'])->name('slot-ttc.update');
    Route::delete('/slot-ttc/{tapTryCollectSlot}', [SlotTtcController::class, 'destroy'])->name('slot-ttc.destroy');

    // Pesanan dan pembayaran
    Route::get('/pesanan', [PesananAdminController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{order}', [PesananAdminController::class, 'show'])->name('pesanan.show');
    Route::put('/pesanan/{order}/status', [PesananAdminController::class, 'ubahStatus'])->name('pesanan.status');
    Route::get('/pembayaran', [PesananAdminController::class, 'pembayaran'])->name('pembayaran.index');
    Route::put('/pembayaran/{payment}/periksa', [PesananAdminController::class, 'periksaPembayaran'])->name('pembayaran.periksa');

    // Retur
    Route::get('/retur', [ReturAdminController::class, 'index'])->name('retur.index');
    Route::get('/retur/{returnRequest}', [ReturAdminController::class, 'show'])->name('retur.show');
    Route::put('/retur/{returnRequest}/status', [ReturAdminController::class, 'ubahStatus'])->name('retur.status');

    // Donasi
    Route::get('/donasi', [DonasiAdminController::class, 'index'])->name('donasi.index');
    Route::get('/donasi/{donation}', [DonasiAdminController::class, 'show'])->name('donasi.show');
    Route::put('/donasi/{donation}/validasi', [DonasiAdminController::class, 'validasi'])->name('donasi.validasi');

    // Tap Try and Collect
    Route::get('/tap-try-collect', [TtcAdminController::class, 'index'])->name('ttc.index');
    Route::get('/tap-try-collect/{tapTryCollect}', [TtcAdminController::class, 'show'])->name('ttc.show');
    Route::put('/tap-try-collect/{tapTryCollect}/status', [TtcAdminController::class, 'ubahStatus'])->name('ttc.status');

    // Keluhan pelanggan
    Route::get('/keluhan', [KeluhanAdminController::class, 'index'])->name('keluhan.index');
    Route::get('/keluhan/{complaint}', [KeluhanAdminController::class, 'show'])->name('keluhan.show');
    Route::put('/keluhan/{complaint}', [KeluhanAdminController::class, 'jawab'])->name('keluhan.jawab');

    // Pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/{user}', [PelangganController::class, 'show'])->name('pelanggan.show');

    // Voucher (CRUD)
    Route::get('/voucher', [VoucherController::class, 'index'])->name('voucher.index');
    Route::post('/voucher', [VoucherController::class, 'store'])->name('voucher.store');
    Route::put('/voucher/{voucher}', [VoucherController::class, 'update'])->name('voucher.update');
    Route::delete('/voucher/{voucher}', [VoucherController::class, 'destroy'])->name('voucher.destroy');

    // Laporan penjualan dan pengaturan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
});
