@extends('layouts.app')

@section('judul', 'Keranjang Belanja')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li>Keranjang Belanja</li>
        </ol>
        <h1>Keranjang Belanja</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Centang barang yang ingin dibayar sekarang. Barang yang tidak dicentang tetap tersimpan di keranjang.
        </p>
    </div>

    @if($daftar->count() > 0)
        <div class="row g-5">
            <div class="col-lg-8">

                <div class="d-flex gap-2 flex-wrap mb-3">
                    <form action="{{ route('keranjang.pilih-semua') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pilih" value="1">
                        <button type="submit" class="cb-tombol cb-tombol--garis cb-tombol--kecil">
                            <i class="bi bi-check2-square"></i> Centang semua
                        </button>
                    </form>
                    <form action="{{ route('keranjang.pilih-semua') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pilih" value="0">
                        <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--kecil">
                            <i class="bi bi-square"></i> Lepas semua centang
                        </button>
                    </form>
                </div>

                @foreach($daftar as $baris)
                    @php
                        $varian = $baris->varian;
                        $produk = $varian ? $varian->produk : null;
                        $harga = $produk ? $produk->hargaJual() : 0;
                    @endphp
                    <div class="cb-barang">
                        <div class="cb-barang__gambar">
                            @if($produk)
                                <a href="{{ route('produk.show', $produk) }}">
                                    <img src="{{ gambar_produk($produk->gambarUtama()?->path) }}" alt="{{ $produk->name }}">
                                </a>
                            @else
                                <span class="cb-produk__kosong">Produk tidak tersedia</span>
                            @endif
                        </div>

                        <div>
                            <div class="d-flex gap-2 align-items-start">
                                <form action="{{ route('keranjang.pilih', $baris) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--kecil"
                                            aria-label="{{ $baris->selected ? 'Lepas centang barang ini' : 'Centang barang ini' }}">
                                        <i class="bi {{ $baris->selected ? 'bi-check-square-fill' : 'bi-square' }}"></i>
                                    </button>
                                </form>
                                <div>
                                    <h2 class="cb-barang__nama mb-1">
                                        @if($produk)
                                            <a href="{{ route('produk.show', $produk) }}">{{ $produk->name }}</a>
                                        @else
                                            Produk tidak tersedia
                                        @endif
                                    </h2>
                                    <p class="cb-barang__ket mb-1">
                                        {{ $varian ? $varian->label() : '-' }}
                                        @if($varian)
                                            · Sisa stok {{ $varian->stock }}
                                        @endif
                                    </p>
                                    <p class="mb-0" style="font-weight: 600">{{ rupiah($harga) }}</p>
                                </div>
                            </div>

                            <div class="cb-barang__aksi">
                                <form action="{{ route('keranjang.update', $baris) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="cb-jumlah-atur">
                                        <button type="button" data-jumlah="kurang" data-medan="jml-{{ $baris->id }}"
                                                data-kirim="ya" aria-label="Kurangi jumlah">&minus;</button>
                                        <label class="cb-sr" for="jml-{{ $baris->id }}">Jumlah</label>
                                        <input type="number" id="jml-{{ $baris->id }}" name="quantity"
                                               value="{{ $baris->quantity }}" min="1"
                                               max="{{ $varian ? max($varian->stock, 1) : 1 }}">
                                        <button type="button" data-jumlah="tambah" data-medan="jml-{{ $baris->id }}"
                                                data-kirim="ya" aria-label="Tambah jumlah">+</button>
                                    </div>
                                    <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--kecil mt-2">
                                        Perbarui jumlah
                                    </button>
                                </form>

                                <form action="{{ route('keranjang.destroy', $baris) }}" method="POST"
                                      data-konfirmasi="Keluarkan barang ini dari keranjang?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--kecil">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="text-end" style="font-weight: 600">{{ rupiah($harga * $baris->quantity) }}</div>
                    </div>
                @endforeach

                <a class="cb-tombol cb-tombol--polos mt-4 d-inline-block" href="{{ route('produk.index') }}">
                    <i class="bi bi-arrow-left"></i> Lanjut berbelanja
                </a>
            </div>

            <div class="col-lg-4">
                <div class="cb-panel cb-menempel">
                    <h2 style="font-size: var(--cb-h4)">Ringkasan Belanja</h2>

                    <ul class="cb-rincian-biaya">
                        <li><span>{{ $jumlahDipilih }} barang dicentang</span><span>{{ rupiah($subtotal) }}</span></li>
                        <li class="cb-jumlah"><span>Subtotal</span><span>{{ rupiah($subtotal) }}</span></li>
                    </ul>

                    @if($subtotal >= $batasGratisOngkir)
                        <p class="cb-catatan cb-catatan--berhasil mt-3">
                            Belanja Anda sudah memenuhi syarat gratis ongkos kirim.
                        </p>
                    @else
                        <p class="cb-catatan cb-catatan--ingat mt-3">
                            Tambah belanja {{ rupiah($batasGratisOngkir - $subtotal) }} lagi untuk mendapat gratis ongkos kirim.
                        </p>
                    @endif

                    @if($subtotal > 0)
                        @auth
                            <a class="cb-tombol cb-tombol--utama cb-tombol--penuh mt-4" href="{{ route('checkout.index') }}">
                                Lanjut ke pembayaran
                            </a>
                        @endauth
                        @guest
                            <a class="cb-tombol cb-tombol--utama cb-tombol--penuh mt-4" href="{{ route('login') }}">
                                Masuk dulu untuk melanjutkan
                            </a>
                            <p class="mt-3 mb-0" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                                Isi keranjang Anda tetap tersimpan setelah masuk ke akun.
                            </p>
                        @endguest
                    @else
                        <p class="cb-catatan mt-4 mb-0">Centang minimal satu barang untuk melanjutkan ke pembayaran.</p>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="cb-kosong">
            <h2>Keranjang masih kosong</h2>
            <p>Belum ada barang di keranjang Anda. Lihat koleksi terbaru Colorbox dan pilih yang Anda suka.</p>
            <a class="cb-tombol cb-tombol--utama" href="{{ route('produk.index') }}">Mulai belanja</a>
        </div>
    @endif

</div>
@endsection
