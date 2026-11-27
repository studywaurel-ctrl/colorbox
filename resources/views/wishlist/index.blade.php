@extends('layouts.app')

@section('judul', 'Wishlist')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li>Wishlist</li>
        </ol>
        <h1>Wishlist</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            {{ $daftar->count() }} produk tersimpan. Wishlist membantu Anda menandai produk yang ingin dibeli nanti.
        </p>
    </div>

    @if($daftar->count() > 0)
        <section class="cb-bagian cb-bagian--rapat">
            <div class="cb-kisi">
                @foreach($daftar as $baris)
                    @if($baris->produk)
                        <div>
                            <x-kartu-produk :produk="$baris->produk" :disukai="true" />
                            <form action="{{ route('wishlist.destroy', $baris->produk) }}" method="POST" class="mt-2"
                                  data-konfirmasi="Keluarkan produk ini dari wishlist?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--kecil">
                                    <i class="bi bi-trash"></i> Keluarkan dari wishlist
                                </button>
                            </form>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @else
        <div class="cb-kosong">
            <h2>Wishlist masih kosong</h2>
            <p>Tekan ikon hati pada produk yang Anda sukai, lalu produknya akan muncul di halaman ini.</p>
            <a class="cb-tombol cb-tombol--utama" href="{{ route('produk.index') }}">Mulai belanja</a>
        </div>
    @endif

</div>
@endsection
