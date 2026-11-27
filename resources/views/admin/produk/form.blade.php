@extends('layouts.admin')

@section('judul', $produk ? 'Ubah Produk' : 'Tambah Produk')

@section('isi')

@php
    // Baris varian yang ditampilkan: isian lama bila validasi gagal,
    // data produk bila sedang mengubah, atau satu baris kosong bila produk baru.
    $barisVarian = old('varian');
    if (! $barisVarian) {
        $barisVarian = [];
        if ($produk) {
            foreach ($produk->varian as $varian) {
                $barisVarian[] = [
                    'id' => $varian->id,
                    'color' => $varian->color,
                    'color_hex' => $varian->color_hex,
                    'size' => $varian->size,
                    'sku' => $varian->sku,
                    'stock' => $varian->stock,
                ];
            }
        }
        if (count($barisVarian) === 0) {
            $barisVarian[] = ['id' => null, 'color' => '', 'color_hex' => '', 'size' => '', 'sku' => '', 'stock' => 0];
        }
    }
@endphp

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">{{ $produk ? 'Ubah Produk: '.$produk->name : 'Tambah Produk Baru' }}</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Alamat halaman produk (slug) dibuat otomatis dari nama produk.
    </p>

    <form class="cb-borang mt-3" method="POST" enctype="multipart/form-data"
          action="{{ $produk ? route('admin.produk.update', $produk) : route('admin.produk.store') }}">
        @csrf
        @if($produk)
            @method('PUT')
        @endif

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="name">Nama produk</label>
                <input class="cb-masukan" type="text" id="name" name="name" required maxlength="150"
                       value="{{ old('name', $produk->name ?? '') }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="category_id">Kategori</label>
                <select class="cb-masukan" id="category_id" name="category_id" required>
                    <option value="">Pilih kategori</option>
                    @foreach($kategori as $baris)
                        <option value="{{ $baris->id }}" @selected((string) old('category_id', $produk->category_id ?? '') === (string) $baris->id)>
                            {{ $baris->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="price">Harga normal (Rp)</label>
                <input class="cb-masukan" type="number" id="price" name="price" min="0" required
                       value="{{ old('price', $produk->price ?? '') }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="discount_price">Harga diskon (Rp)</label>
                <input class="cb-masukan" type="number" id="discount_price" name="discount_price" min="0"
                       value="{{ old('discount_price', $produk->discount_price ?? '') }}">
                <p class="cb-isian__bantu">Kosongkan bila produk tidak sedang diskon.</p>
            </div>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="sku">SKU</label>
                <input class="cb-masukan" type="text" id="sku" name="sku" maxlength="40"
                       value="{{ old('sku', $produk->sku ?? '') }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="weight">Berat (gram)</label>
                <input class="cb-masukan" type="number" id="weight" name="weight" min="1" required
                       value="{{ old('weight', $produk->weight ?? 300) }}">
            </div>
        </div>

        <div class="cb-isian">
            <label class="cb-label" for="description">Deskripsi</label>
            <textarea class="cb-masukan" id="description" name="description" rows="4">{{ old('description', $produk->description ?? '') }}</textarea>
        </div>

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="material">Bahan</label>
                <input class="cb-masukan" type="text" id="material" name="material" maxlength="150"
                       value="{{ old('material', $produk->material ?? '') }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="care">Perawatan</label>
                <input class="cb-masukan" type="text" id="care" name="care" maxlength="191"
                       value="{{ old('care', $produk->care ?? '') }}">
            </div>
        </div>

        <div class="cb-isian">
            <label class="cb-label" for="collection">Koleksi</label>
            <input class="cb-masukan" type="text" id="collection" name="collection" maxlength="80"
                   value="{{ old('collection', $produk->collection ?? '') }}">
            <p class="cb-isian__bantu">Contoh: Colorbox X Liunic. Boleh dikosongkan.</p>
        </div>

        <div class="cb-isian">
            <label class="cb-label">Penanda</label>
            <div class="cb-pilihan__daftar">
                <label class="cb-pilihan__label">
                    <input type="checkbox" name="is_new" value="1" @checked(old('is_new', $produk->is_new ?? false))>
                    Tandai sebagai produk baru
                </label>
                <label class="cb-pilihan__label">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $produk->is_active ?? true))>
                    Tampilkan di katalog (aktif)
                </label>
            </div>
        </div>

        <hr>

        <h3 style="font-size: var(--cb-h5)">Varian Warna dan Ukuran</h3>
        <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
            Setiap baris adalah satu gabungan warna dan ukuran. Baris yang warna dan ukurannya kosong akan diabaikan.
        </p>

        <div class="ad-gulir">
            <table class="ad-tabel" id="tabel-varian">
                <thead>
                <tr>
                    <th>Warna</th>
                    <th>Kode Warna (hex)</th>
                    <th>Ukuran</th>
                    <th>SKU Varian</th>
                    <th>Stok</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="isi-varian">
                @foreach($barisVarian as $nomor => $baris)
                    <tr>
                        <td>
                            <input type="hidden" name="varian[{{ $nomor }}][id]" value="{{ $baris['id'] ?? '' }}">
                            <input class="cb-masukan" type="text" maxlength="40" name="varian[{{ $nomor }}][color]"
                                   value="{{ $baris['color'] ?? '' }}" placeholder="OFF WHITE">
                        </td>
                        <td>
                            <input class="cb-masukan" type="text" maxlength="10" name="varian[{{ $nomor }}][color_hex]"
                                   value="{{ $baris['color_hex'] ?? '' }}" placeholder="#F5F0E8">
                        </td>
                        <td>
                            <input class="cb-masukan" type="text" maxlength="10" name="varian[{{ $nomor }}][size]"
                                   value="{{ $baris['size'] ?? '' }}" placeholder="M">
                        </td>
                        <td>
                            <input class="cb-masukan" type="text" maxlength="50" name="varian[{{ $nomor }}][sku]"
                                   value="{{ $baris['sku'] ?? '' }}">
                        </td>
                        <td>
                            <input class="cb-masukan" type="number" min="0" name="varian[{{ $nomor }}][stock]"
                                   value="{{ $baris['stock'] ?? 0 }}" style="max-width: 110px">
                        </td>
                        <td>
                            <button class="cb-tombol cb-tombol--kecil cb-tombol--polos" type="button" data-hapus-baris>Hapus</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Contoh baris kosong yang disalin JavaScript ketika tombol di bawah ditekan --}}
        <template id="contoh-varian">
            <tr>
                <td>
                    <input type="hidden" name="varian[__i__][id]" value="">
                    <input class="cb-masukan" type="text" maxlength="40" name="varian[__i__][color]" placeholder="OFF WHITE">
                </td>
                <td><input class="cb-masukan" type="text" maxlength="10" name="varian[__i__][color_hex]" placeholder="#F5F0E8"></td>
                <td><input class="cb-masukan" type="text" maxlength="10" name="varian[__i__][size]" placeholder="M"></td>
                <td><input class="cb-masukan" type="text" maxlength="50" name="varian[__i__][sku]"></td>
                <td><input class="cb-masukan" type="number" min="0" name="varian[__i__][stock]" value="0" style="max-width: 110px"></td>
                <td><button class="cb-tombol cb-tombol--kecil cb-tombol--polos" type="button" data-hapus-baris>Hapus</button></td>
            </tr>
        </template>

        <button class="cb-tombol cb-tombol--garis cb-tombol--kecil mt-2" type="button" id="tambah-varian"
                data-mulai="{{ count($barisVarian) }}">
            <i class="bi bi-plus-lg"></i> Tambah Baris Varian
        </button>

        <hr>

        <div class="cb-isian">
            <label class="cb-label" for="gambar">Gambar produk (boleh lebih dari satu)</label>
            <input class="cb-masukan" type="file" id="gambar" name="gambar[]" accept="image/*" multiple>
            <p class="cb-isian__bantu">Ukuran setiap berkas paling besar 2MB. Boleh dikosongkan.</p>
        </div>

        @if($produk && $produk->gambar->count() > 0)
            <div class="cb-galeri mt-2">
                @foreach($produk->gambar->sortBy('sort_order') as $gambar)
                    <img src="{{ gambar_produk($gambar->path) }}" alt="{{ $produk->name }}" width="90" height="120" style="object-fit: cover">
                @endforeach
            </div>
        @endif

        <div class="d-flex gap-2 mt-4">
            <button class="cb-tombol cb-tombol--utama" type="submit">{{ $produk ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
            <a class="cb-tombol cb-tombol--polos" href="{{ route('admin.produk.index') }}">Batal</a>
        </div>
    </form>
</div>

@endsection

@push('skrip')
<script>
// Menyalin satu baris varian kosong. Nomor baris baru dilanjutkan dari
// jumlah baris yang sudah ada, dibaca dari atribut data-mulai.
document.addEventListener('DOMContentLoaded', function () {
    var tombol = document.getElementById('tambah-varian');
    var contoh = document.getElementById('contoh-varian');
    var isi = document.getElementById('isi-varian');
    if (!tombol || !contoh || !isi) { return; }

    var nomor = parseInt(tombol.dataset.mulai || '0', 10);

    tombol.addEventListener('click', function () {
        var tulisan = contoh.innerHTML.split('__i__').join(String(nomor));
        var pembungkus = document.createElement('tbody');
        pembungkus.innerHTML = tulisan;
        while (pembungkus.firstElementChild) {
            isi.appendChild(pembungkus.firstElementChild);
        }
        nomor = nomor + 1;
    });

    // Tombol hapus baris cukup menghilangkan baris dari formulir
    isi.addEventListener('click', function (peristiwa) {
        var sasaran = peristiwa.target.closest('[data-hapus-baris]');
        if (!sasaran) { return; }
        if (isi.querySelectorAll('tr').length <= 1) {
            window.alert('Paling sedikit harus ada satu baris varian.');
            return;
        }
        sasaran.closest('tr').remove();
    });
});
</script>
@endpush
