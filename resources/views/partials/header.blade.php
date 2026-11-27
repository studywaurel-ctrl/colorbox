@php
    // Jumlah kecil di ikon keranjang dan wishlist. Dihitung sederhana di sini
    // supaya setiap halaman memperlihatkan angka terbaru.
    $jumlahKeranjang = 0;
    $jumlahWishlist = 0;
    if (auth()->check()) {
        $keranjang = \App\Models\Cart::where('user_id', auth()->id())->first();
        $jumlahKeranjang = $keranjang ? $keranjang->item()->sum('quantity') : 0;
        $jumlahWishlist = \App\Models\Wishlist::where('user_id', auth()->id())->count();
    }
    $kategoriMenu = \App\Models\Category::whereNull('parent_id')->orderBy('sort_order')->get();
@endphp

<header class="cb-kepala">
    <div class="cb-pita">
        Gratis ongkos kirim untuk belanja mulai {{ rupiah(setting('free_shipping_threshold', 350000)) }}.
        <a href="{{ route('bantuan.show', 'pengiriman') }}">Selengkapnya</a>
    </div>

    <div class="cb-wadah">
        <div class="cb-kepala__baris">
            <a class="cb-merek" href="{{ route('beranda') }}">
                <img src="{{ asset('images/logo/colorbox-wordmark.png') }}" alt="Colorbox" height="26">
            </a>

            <nav class="cb-nav" aria-label="Menu utama">
                <a class="cb-nav__tautan" href="{{ route('beranda') }}" @if(request()->routeIs('beranda')) aria-current="page" @endif>Beranda</a>
                <a class="cb-nav__tautan" href="{{ route('produk.baru') }}" @if(request()->routeIs('produk.baru')) aria-current="page" @endif>New Arrival</a>
                <a class="cb-nav__tautan" href="{{ route('produk.index') }}" @if(request()->routeIs('produk.index')) aria-current="page" @endif>Semua Produk</a>
                <a class="cb-nav__tautan" href="{{ route('vto.index') }}" @if(request()->routeIs('vto.*')) aria-current="page" @endif>Virtual Try On</a>
                <a class="cb-nav__tautan" href="{{ route('ttc.index') }}" @if(request()->routeIs('ttc.*')) aria-current="page" @endif>Tap Try and Collect</a>
                <a class="cb-nav__tautan" href="{{ route('donasi.index') }}" @if(request()->routeIs('donasi.*')) aria-current="page" @endif>Donasi Poin</a>
                <a class="cb-nav__tautan" href="{{ route('toko.index') }}" @if(request()->routeIs('toko.*')) aria-current="page" @endif>Daftar Toko</a>
            </nav>

            <div class="cb-aksi">
                <button type="button" class="cb-ikon" id="buka-cari" aria-expanded="false" aria-label="Cari produk">
                    <i class="bi bi-search"></i>
                </button>
                <a class="cb-ikon" href="{{ route('wishlist.index') }}" aria-label="Wishlist">
                    <i class="bi bi-heart"></i>
                    @if($jumlahWishlist > 0)<span class="cb-lencana-jumlah">{{ $jumlahWishlist }}</span>@endif
                </a>
                <a class="cb-ikon" href="{{ route('keranjang.index') }}" aria-label="Keranjang belanja">
                    <i class="bi bi-bag"></i>
                    @if($jumlahKeranjang > 0)<span class="cb-lencana-jumlah">{{ $jumlahKeranjang }}</span>@endif
                </a>
                <a class="cb-ikon" href="{{ auth()->check() ? route('akun.index') : route('login') }}" aria-label="Akun saya">
                    <i class="bi bi-person"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="cb-subnav">
        <div class="cb-wadah">
            <div class="cb-subnav__isi">
                @foreach($kategoriMenu as $kategori)
                    <a href="{{ route('produk.index', ['kategori' => $kategori->slug]) }}">{{ $kategori->name }}</a>
                @endforeach
                <a href="{{ route('produk.index', ['koleksi' => 'Colorbox x Liunic']) }}">Kolaborasi</a>
                <a href="{{ route('bantuan.show', 'panduan-ukuran') }}">Panduan Ukuran</a>
                <a href="{{ route('lacak.index') }}">Lacak Pesanan</a>
                <a href="{{ route('bantuan.index') }}">Bantuan</a>
            </div>
        </div>
    </div>

    <div class="cb-cari" id="kotak-cari">
        <div class="cb-wadah">
            <form class="cb-cari__kotak" action="{{ route('produk.index') }}" method="GET">
                <i class="bi bi-search"></i>
                <label class="cb-sr" for="kata-cari">Kata kunci pencarian</label>
                <input type="search" id="kata-cari" name="cari" value="{{ request('cari') }}"
                       placeholder="Cari produk, misalnya kemeja linen" autocomplete="off">
                <button type="submit" class="cb-tombol cb-tombol--kecil cb-tombol--utama">Cari</button>
                <button type="button" class="cb-ikon" id="tutup-cari" aria-label="Tutup pencarian">
                    <i class="bi bi-x-lg"></i>
                </button>
            </form>
            <div class="cb-cari__saran">
                <div>
                    <p class="cb-cari__judul">Kategori</p>
                    <ul class="cb-cari__daftar">
                        @foreach($kategoriMenu as $kategori)
                            <li><a href="{{ route('produk.index', ['kategori' => $kategori->slug]) }}">{{ $kategori->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="cb-cari__judul">Pencarian populer</p>
                    <ul class="cb-cari__daftar">
                        <li><a href="{{ route('produk.index', ['cari' => 'shirt']) }}">Kemeja</a></li>
                        <li><a href="{{ route('produk.index', ['cari' => 'jeans']) }}">Jeans</a></li>
                        <li><a href="{{ route('produk.index', ['cari' => 'skirt']) }}">Rok</a></li>
                        <li><a href="{{ route('produk.index', ['cari' => 'bag']) }}">Tas</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
