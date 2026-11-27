@extends('layouts.admin')

@section('judul', 'Rincian Produk')

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">{{ $produk->name }}</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                {{ $produk->kategori?->name ?? 'Tanpa kategori' }} ·
                slug: {{ $produk->slug }} ·
                SKU: {{ $produk->sku ?: '-' }}
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.produk.edit', $produk) }}">Ubah Produk</a>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.produk.index') }}">Kembali</a>
        </div>
    </div>

    <div class="ad-angka mt-3">
        <div>
            <p class="ad-angka__nilai mb-0">{{ rupiah($produk->hargaJual()) }}</p>
            <p class="ad-angka__label mb-0">Harga jual</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $produk->discount_price ? rupiah($produk->price) : '-' }}</p>
            <p class="ad-angka__label mb-0">Harga sebelum diskon</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $produk->totalStok() }}</p>
            <p class="ad-angka__label mb-0">Total stok</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $produk->weight }} g</p>
            <p class="ad-angka__label mb-0">Berat</p>
        </div>
    </div>

    <div class="mt-3" style="font-size: var(--cb-kecil)">
        <p class="mb-1"><strong>Status:</strong>
            @if($produk->is_active)
                <span class="cb-lencana cb-lencana--berhasil">Aktif</span>
            @else
                <span class="cb-lencana cb-lencana--ingat">Nonaktif</span>
            @endif
            @if($produk->is_new)<span class="cb-lencana cb-lencana--baru">Produk Baru</span>@endif
        </p>
        <p class="mb-1"><strong>Koleksi:</strong> {{ $produk->collection ?: '-' }}</p>
        <p class="mb-1"><strong>Bahan:</strong> {{ $produk->material ?: '-' }}</p>
        <p class="mb-1"><strong>Perawatan:</strong> {{ $produk->care ?: '-' }}</p>
        <p class="mb-0"><strong>Deskripsi:</strong> {{ $produk->description ?: '-' }}</p>
    </div>
</div>

<div class="ad-kartu">
    <h3 style="font-size: var(--cb-h5)">Gambar Produk</h3>
    @if($produk->gambar->count() > 0)
        <div class="cb-galeri mt-2">
            @foreach($produk->gambar->sortBy('sort_order') as $gambar)
                <figure class="m-0">
                    <img src="{{ gambar_produk($gambar->path) }}" alt="{{ $produk->name }}"
                         width="120" height="160" style="object-fit: cover">
                    <figcaption style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                        {{ $gambar->color ?: 'tanpa warna' }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    @else
        <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">Belum ada gambar untuk produk ini.</p>
    @endif
</div>

<div class="ad-kartu">
    <h3 style="font-size: var(--cb-h5)">Varian dan Stok</h3>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Ubah beberapa stok sekaligus lalu tekan Simpan Stok.
    </p>

    <form action="{{ route('admin.produk.stok', $produk) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="ad-gulir">
            <table class="ad-tabel">
                <thead>
                <tr>
                    <th>Warna</th>
                    <th>Kode Warna</th>
                    <th>Ukuran</th>
                    <th>SKU</th>
                    <th>Stok</th>
                </tr>
                </thead>
                <tbody>
                @forelse($produk->varian as $varian)
                    <tr>
                        <td>{{ $varian->color }}</td>
                        <td>{{ $varian->color_hex ?: '-' }}</td>
                        <td>{{ $varian->size }}</td>
                        <td>{{ $varian->sku ?: '-' }}</td>
                        <td>
                            <input class="cb-masukan" type="number" min="0" style="max-width: 120px"
                                   name="stok[{{ $varian->id }}]" value="{{ $varian->stock }}">
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Produk ini belum punya varian.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($produk->varian->count() > 0)
            <button class="cb-tombol cb-tombol--utama cb-tombol--kecil mt-3" type="submit">Simpan Stok</button>
        @endif
    </form>
</div>

@endsection
