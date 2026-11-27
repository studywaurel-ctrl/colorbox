@props(['produk', 'disukai' => null])

@php
    // Kartu produk dipakai di beranda, katalog, wishlist, dan halaman toko.
    // Penanda wishlist dihitung di sini bila tidak dikirim dari luar.
    if ($disukai === null) {
        $disukai = auth()->check()
            ? \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $produk->id)->exists()
            : false;
    }

    $gambarUtama = $produk->gambarUtama();
    $adaDiskon = $produk->discount_price && $produk->discount_price < $produk->price;
    $hemat = $adaDiskon ? (int) round(($produk->price - $produk->discount_price) / $produk->price * 100) : 0;
    $stok = $produk->totalStok();
@endphp

<article class="cb-produk">
    <form action="{{ route('wishlist.store') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $produk->id }}">
        <button type="submit" class="cb-suka" aria-pressed="{{ $disukai ? 'true' : 'false' }}"
                aria-label="Simpan {{ $produk->name }} ke wishlist">
            <i class="bi {{ $disukai ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        </button>
    </form>

    @if($adaDiskon)
        <span class="cb-lencana cb-lencana--diskon cb-lencana--tempel">Hemat {{ $hemat }}%</span>
    @elseif($produk->is_new)
        <span class="cb-lencana cb-lencana--baru cb-lencana--tempel">Baru</span>
    @endif

    <a href="{{ route('produk.show', $produk) }}" aria-label="{{ $produk->name }}">
        <span class="cb-produk__gambar">
            <img src="{{ gambar_produk($gambarUtama ? $gambarUtama->path : null) }}" alt="{{ $produk->name }}" loading="lazy">
            @if($stok < 1)
                <span class="cb-produk__kosong">Stok habis</span>
            @endif
        </span>
    </a>

    <p class="cb-produk__kategori">{{ $produk->kategori ? $produk->kategori->name : 'Colorbox' }}</p>
    <h3 class="cb-produk__nama"><a href="{{ route('produk.show', $produk) }}">{{ $produk->name }}</a></h3>
    <p class="cb-produk__harga">
        <span>{{ rupiah($produk->hargaJual()) }}</span>
        @if($adaDiskon)
            <span class="cb-produk__coret">{{ rupiah($produk->price) }}</span>
        @endif
    </p>

    @if($produk->varian->count() > 0)
        <div class="cb-produk__warna">
            @foreach($produk->varian->unique('color')->take(5) as $varian)
                <span class="cb-titik-warna" style="background: {{ $varian->color_hex ?: '#e8e4de' }}" title="{{ $varian->color }}"></span>
            @endforeach
        </div>
    @endif
</article>
