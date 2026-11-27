@extends('layouts.admin')

@section('judul', 'Produk dan Stok')

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">Produk dan Stok</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                {{ $daftar->total() }} produk tercatat.
            </p>
        </div>
        <a class="cb-tombol cb-tombol--utama cb-tombol--kecil" href="{{ route('admin.produk.create') }}">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.produk.index') }}" method="GET">
        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="cari">Cari nama atau SKU</label>
                <input class="cb-masukan" type="search" id="cari" name="cari" value="{{ $cari }}" placeholder="Misalnya: kemeja">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="kategori">Kategori</label>
                <select class="cb-masukan" id="kategori" name="kategori">
                    <option value="">Semua kategori</option>
                    @foreach($kategori as $baris)
                        <option value="{{ $baris->id }}" @selected((string) $kategoriId === (string) $baris->id)>{{ $baris->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-2">
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.produk.index') }}">Bersihkan</a>
        </div>
    </form>
</div>

<div class="ad-kartu">
    <div class="ad-gulir">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Total Stok</th>
                <th>Penanda</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $produk)
                <tr>
                    <td>
                        <a href="{{ route('admin.produk.show', $produk) }}">{{ $produk->name }}</a><br>
                        <span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">{{ $produk->sku ?: 'tanpa SKU' }}</span>
                    </td>
                    <td>{{ $produk->kategori?->name ?? '-' }}</td>
                    <td>
                        {{ rupiah($produk->hargaJual()) }}
                        @if($produk->discount_price)
                            <br><span class="cb-produk__coret">{{ rupiah($produk->price) }}</span>
                        @endif
                    </td>
                    <td>{{ $produk->totalStok() }}</td>
                    <td>
                        @if($produk->is_new)<span class="cb-lencana cb-lencana--baru">Baru</span>@endif
                        @if($produk->is_active)
                            <span class="cb-lencana cb-lencana--berhasil">Aktif</span>
                        @else
                            <span class="cb-lencana cb-lencana--ingat">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2 flex-wrap">
                            <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.produk.edit', $produk) }}">Ubah</a>
                            <form action="{{ route('admin.produk.destroy', $produk) }}" method="POST"
                                  data-konfirmasi="Hapus produk {{ $produk->name }} beserta varian dan gambarnya?">
                                @csrf
                                @method('DELETE')
                                <button class="cb-tombol cb-tombol--kecil cb-tombol--polos" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Produk tidak ditemukan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
