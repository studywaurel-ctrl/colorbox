@extends('layouts.admin')

@section('judul', 'Kategori')

@section('isi')

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Tambah Kategori</h2>
    <form class="cb-borang mt-2" action="{{ route('admin.kategori.store') }}" method="POST">
        @csrf
        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="name">Nama kategori</label>
                <input class="cb-masukan" type="text" id="name" name="name" maxlength="60" required value="{{ old('name') }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="parent_id">Kategori induk</label>
                <select class="cb-masukan" id="parent_id" name="parent_id">
                    <option value="">Tanpa induk</option>
                    @foreach($semua as $baris)
                        <option value="{{ $baris->id }}" @selected((string) old('parent_id') === (string) $baris->id)>{{ $baris->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="cb-isian">
            <label class="cb-label" for="sort_order">Urutan tampil</label>
            <input class="cb-masukan" type="number" id="sort_order" name="sort_order" min="0" max="999" value="{{ old('sort_order', 0) }}" style="max-width: 160px">
            <p class="cb-isian__bantu">Angka kecil ditampilkan lebih dahulu.</p>
        </div>
        <button class="cb-tombol cb-tombol--utama cb-tombol--kecil" type="submit">Simpan Kategori</button>
    </form>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Daftar Kategori</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        {{ $daftar->total() }} kategori tercatat. Kategori yang masih punya produk tidak bisa dihapus.
    </p>

    <form class="cb-borang mt-2" action="{{ route('admin.kategori.index') }}" method="GET">
        <div class="d-flex gap-2 flex-wrap align-items-end">
            <div class="cb-isian mb-0">
                <label class="cb-label" for="cari">Cari nama kategori</label>
                <input class="cb-masukan" type="search" id="cari" name="cari" value="{{ $cari }}">
            </div>
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.kategori.index') }}">Bersihkan</a>
        </div>
    </form>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Nama</th>
                <th>Induk</th>
                <th>Urutan</th>
                <th>Produk</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $kategori)
                <tr>
                    <td>
                        <form class="d-flex gap-2 flex-wrap align-items-center"
                              action="{{ route('admin.kategori.update', $kategori) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input class="cb-masukan" type="text" name="name" maxlength="60" required
                                   value="{{ $kategori->name }}" style="max-width: 200px">
                            <select class="cb-masukan" name="parent_id" style="max-width: 180px">
                                <option value="">Tanpa induk</option>
                                @foreach($semua as $pilihan)
                                    @if($pilihan->id !== $kategori->id)
                                        <option value="{{ $pilihan->id }}" @selected($kategori->parent_id === $pilihan->id)>{{ $pilihan->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <input class="cb-masukan" type="number" name="sort_order" min="0" max="999"
                                   value="{{ $kategori->sort_order }}" style="max-width: 90px">
                            <button class="cb-tombol cb-tombol--kecil" type="submit">Simpan</button>
                        </form>
                    </td>
                    <td>{{ $kategori->induk?->name ?? '-' }}</td>
                    <td>{{ $kategori->sort_order }}</td>
                    <td>{{ $kategori->produk_count }}</td>
                    <td>
                        <form action="{{ route('admin.kategori.destroy', $kategori) }}" method="POST"
                              data-konfirmasi="Hapus kategori {{ $kategori->name }}?">
                            @csrf
                            @method('DELETE')
                            <button class="cb-tombol cb-tombol--kecil cb-tombol--polos" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada kategori.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
