@extends('layouts.app')

@section('judul', $produk->name)

@push('gaya')
<style>
    /* Penanda warna yang sedang dipilih pada halaman produk */
    [data-warna][aria-pressed='true'] span:first-child {
        border-color: var(--cb-tinta);
        border-width: 2px;
    }
    [data-warna] { border: 0; background: transparent; padding: 0; cursor: pointer; }
</style>
@endpush

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            @if($produk->kategori && $produk->kategori->induk)
                <li><a href="{{ route('produk.index', ['kategori' => $produk->kategori->induk->slug]) }}">{{ $produk->kategori->induk->name }}</a></li>
            @endif
            @if($produk->kategori)
                <li><a href="{{ route('produk.index', ['kategori' => $produk->kategori->slug]) }}">{{ $produk->kategori->name }}</a></li>
            @endif
            <li>{{ $produk->name }}</li>
        </ol>
    </div>

    <div class="cb-produk-utama">

        {{-- Galeri gambar. Gambar disaring mengikuti warna yang dipilih (app.js). --}}
        <div class="cb-galeri" id="galeri-produk">
            @forelse($gambar as $gbr)
                <figure data-gambar-warna="{{ $gbr->color }}" @if($gbr->color !== $warnaAwal) hidden @endif>
                    <img src="{{ gambar_produk($gbr->path) }}" alt="{{ $produk->name }} warna {{ $gbr->color }}">
                </figure>
            @empty
                <figure>
                    <img src="{{ gambar_produk(null) }}" alt="{{ $produk->name }}">
                </figure>
            @endforelse
        </div>

        <div class="cb-rincian">
            <h1 style="font-size: var(--cb-h2)">{{ $produk->name }}</h1>
            <p style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">{{ $produk->sku }}</p>

            <div class="cb-rincian__harga">
                <span>{{ rupiah($produk->hargaJual()) }}</span>
                @if($produk->discount_price && $produk->discount_price < $produk->price)
                    <span class="cb-produk__coret">{{ rupiah($produk->price) }}</span>
                    <span class="cb-lencana cb-lencana--diskon">
                        Hemat {{ (int) round(($produk->price - $produk->discount_price) / $produk->price * 100) }}%
                    </span>
                @endif
            </div>

            <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                @if($produk->ulasan->count() > 0)
                    <i class="bi bi-star-fill" style="color: var(--cb-aksen)"></i>
                    {{ number_format($rataRating, 1, ',', '.') }} dari 5
                    <span style="color: var(--cb-tinta-samar)">({{ $produk->ulasan->count() }} ulasan)</span>
                @else
                    Belum ada ulasan untuk produk ini.
                @endif
                @if($produk->collection)
                    &middot; <a href="{{ route('produk.index', ['koleksi' => $produk->collection]) }}">{{ $produk->collection }}</a>
                @endif
            </p>

            {{-- Formulir tambah ke keranjang berisi pilihan warna, ukuran, dan jumlah --}}
            <form action="{{ route('keranjang.store') }}" method="POST">
                @csrf
                <input type="hidden" name="warna" id="warna-terpilih" value="{{ $warnaAwal }}">

                @if($warna->count() > 0)
                    <div class="cb-pilihan">
                        <div class="cb-pilihan__kepala">
                            <span class="cb-pilihan__label">Warna</span>
                        </div>
                        <div class="cb-pilihan__daftar">
                            @foreach($warna as $w)
                                <button type="button" class="cb-pilih cb-pilih--warna" data-warna="{{ $w->color }}"
                                        aria-pressed="{{ $w->color === $warnaAwal ? 'true' : 'false' }}"
                                        title="{{ $w->color }}" aria-label="Warna {{ $w->color }}">
                                    <span style="background: {{ $w->color_hex ?: '#e8e4de' }}"></span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="cb-pilihan">
                    <div class="cb-pilihan__kepala">
                        <span class="cb-pilihan__label">Ukuran</span>
                        <a class="cb-tautan-lanjut" href="{{ route('bantuan.show', 'panduan-ukuran') }}">Panduan ukuran</a>
                    </div>
                    @php
                        // Varian pertama yang stoknya masih ada pada warna awal dipilih lebih dulu
                        $varianAwal = $produk->varian->first(function ($v) use ($warnaAwal) {
                            return $v->color === $warnaAwal && $v->stock > 0;
                        });
                    @endphp
                    <div class="cb-pilihan__daftar">
                        @foreach($produk->varian as $varian)
                            <label class="cb-pilih" data-ukuran-warna="{{ $varian->color }}"
                                   @if($varian->color !== $warnaAwal) hidden @endif>
                                <input type="radio" name="product_variant_id" value="{{ $varian->id }}"
                                       @if($varian->stock < 1) disabled @endif
                                       @if($varianAwal && $varianAwal->id === $varian->id) checked @endif>
                                <span>{{ $varian->size }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="cb-isian__bantu">Ukuran yang dicoret berarti stoknya sedang habis.</p>
                </div>

                <div class="cb-pilihan">
                    <div class="cb-pilihan__kepala">
                        <span class="cb-pilihan__label">Jumlah</span>
                    </div>
                    <div class="cb-jumlah-atur">
                        <button type="button" data-jumlah="kurang" data-medan="jumlah-produk" aria-label="Kurangi jumlah">&minus;</button>
                        <label class="cb-sr" for="jumlah-produk">Jumlah pembelian</label>
                        <input type="number" id="jumlah-produk" name="quantity" value="1" min="1" max="10">
                        <button type="button" data-jumlah="tambah" data-medan="jumlah-produk" aria-label="Tambah jumlah">+</button>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--penuh"
                            @if($produk->totalStok() < 1) disabled @endif>
                        Tambah ke Keranjang
                    </button>
                </div>
            </form>

            <div class="d-grid gap-2 mt-2">
                <a class="cb-tombol cb-tombol--garis cb-tombol--penuh"
                   href="{{ route('vto.index', ['produk' => $produk->slug]) }}">
                    <i class="bi bi-camera"></i> Coba dengan Virtual Try On
                </a>

                <form action="{{ route('wishlist.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $produk->id }}">
                    <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--penuh">
                        <i class="bi bi-heart"></i> Simpan ke Wishlist
                    </button>
                </form>
            </div>

            {{-- Cek stok di toko, isinya diambil lewat fetch ke route produk.stok --}}
            <div class="cb-panel mt-4">
                <h3 style="font-size: var(--cb-h4)">Cek stok di toko</h3>
                <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                    Pilih warna dan ukuran terlebih dahulu, lalu tekan tombol di bawah untuk melihat stok di setiap toko Colorbox.
                </p>
                <button type="button" class="cb-tombol cb-tombol--garis cb-tombol--kecil" id="cek-stok"
                        data-alamat="{{ route('produk.stok', $produk) }}">
                    Cek stok di toko
                </button>
                <div id="hasil-stok" class="mt-3"></div>
            </div>
        </div>
    </div>

    {{-- Keterangan produk --}}
    <section class="cb-bagian">
        <div class="cb-kisi cb-kisi--tiga">
            <div>
                <h3 style="font-size: var(--cb-h4)">Deskripsi</h3>
                <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                    {{ $produk->description ?: 'Keterangan produk belum tersedia.' }}
                </p>
            </div>
            <div>
                <h3 style="font-size: var(--cb-h4)">Bahan</h3>
                <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                    {{ $produk->material ?: 'Keterangan bahan belum tersedia.' }}
                </p>
            </div>
            <div>
                <h3 style="font-size: var(--cb-h4)">Perawatan</h3>
                <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                    {{ $produk->care ?: 'Keterangan perawatan belum tersedia.' }}
                </p>
            </div>
        </div>
    </section>

    {{-- Tabel stok tiap varian --}}
    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-kepala-bagian">
            <div>
                <h2 style="font-size: var(--cb-h3)">Stok Setiap Varian</h2>
                <p>Jumlah stok yang siap dikirim dari gudang Colorbox.</p>
            </div>
        </div>
        <div class="cb-gulir-datar">
            <table class="cb-tabel cb-tabel--angka">
                <thead>
                <tr>
                    <th>Warna</th>
                    <th>Ukuran</th>
                    <th>Kode</th>
                    <th>Stok</th>
                </tr>
                </thead>
                <tbody>
                @forelse($produk->varian as $varian)
                    <tr>
                        <td>
                            <span class="cb-titik-warna" style="background: {{ $varian->color_hex ?: '#e8e4de' }}"></span>
                            {{ $varian->color }}
                        </td>
                        <td>{{ $varian->size }}</td>
                        <td>{{ $varian->sku }}</td>
                        <td>
                            @if($varian->stock > 0)
                                {{ $varian->stock }}
                            @else
                                <span class="cb-lencana">Habis</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Varian produk belum tersedia.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Ulasan pelanggan --}}
    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-kepala-bagian">
            <div>
                <h2 style="font-size: var(--cb-h3)">Ulasan Pelanggan</h2>
                <p>
                    @if($produk->ulasan->count() > 0)
                        Rata-rata {{ number_format($rataRating, 1, ',', '.') }} dari 5 berdasarkan {{ $produk->ulasan->count() }} ulasan.
                    @else
                        Ulasan ditulis pelanggan setelah pesanannya selesai.
                    @endif
                </p>
            </div>
        </div>

        @forelse($produk->ulasan->sortByDesc('id') as $ulasan)
            <div style="padding-block: var(--cb-s4); border-bottom: 1px solid var(--cb-garis)">
                <p class="mb-1" style="font-weight: 600; font-size: var(--cb-kecil)">
                    {{ $ulasan->pengguna ? $ulasan->pengguna->name : 'Pelanggan Colorbox' }}
                    <span style="color: var(--cb-aksen)">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $ulasan->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </span>
                </p>
                <p class="mb-1" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                    {{ $ulasan->comment ?: 'Pelanggan tidak menuliskan komentar.' }}
                </p>
                <p class="mb-0" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                    {{ tanggal_id($ulasan->created_at, true) }}
                </p>
            </div>
        @empty
            <p class="cb-catatan">Belum ada ulasan untuk produk ini.</p>
        @endforelse
    </section>

    {{-- Produk terkait dari kategori yang sama --}}
    @if($terkait->count() > 0)
        <section class="cb-bagian">
            <div class="cb-kepala-bagian">
                <div>
                    <h2 style="font-size: var(--cb-h3)">Produk Terkait</h2>
                    <p>Pilihan lain dari kategori {{ $produk->kategori ? $produk->kategori->name : 'yang sama' }}.</p>
                </div>
            </div>
            <div class="cb-kisi">
                @foreach($terkait as $lain)
                    <x-kartu-produk :produk="$lain" />
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection

@push('skrip')
<script>
// Mengambil stok tiap toko untuk varian yang sedang dipilih.
document.addEventListener('DOMContentLoaded', function () {
    var tombol = document.getElementById('cek-stok');
    var hasil = document.getElementById('hasil-stok');
    if (!tombol || !hasil) { return; }

    tombol.addEventListener('click', function () {
        var pilihan = document.querySelector('input[name="product_variant_id"]:checked');
        if (!pilihan) {
            hasil.innerHTML = '<p class="cb-catatan">Pilih warna dan ukuran terlebih dahulu.</p>';
            return;
        }

        hasil.innerHTML = '<p class="cb-catatan">Sedang mengambil data stok toko...</p>';

        fetch(tombol.dataset.alamat + '?product_variant_id=' + pilihan.value, {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (jawaban) { return jawaban.json(); })
        .then(function (data) {
            if (data.pesan) {
                hasil.innerHTML = '<p class="cb-catatan">' + data.pesan + '</p>';
                return;
            }

            hasil.innerHTML = '';

            var judul = document.createElement('p');
            judul.className = 'cb-catatan';
            judul.textContent = 'Stok untuk varian ' + data.varian + '.';
            hasil.appendChild(judul);

            data.toko.forEach(function (toko) {
                var baris = document.createElement('div');
                baris.className = 'cb-toko';

                var kiri = document.createElement('div');

                var nama = document.createElement('div');
                nama.className = 'cb-toko__nama';
                nama.textContent = toko.nama;
                kiri.appendChild(nama);

                var ket = document.createElement('div');
                ket.className = 'cb-toko__ket';
                ket.textContent = toko.kota + ' · ' + toko.lantai + ' · ' + toko.jam;
                kiri.appendChild(ket);

                if (toko.ttc) {
                    var layanan = document.createElement('div');
                    layanan.className = 'cb-toko__layanan';
                    var tanda = document.createElement('span');
                    tanda.className = 'cb-lencana';
                    tanda.textContent = 'Tap Try and Collect';
                    layanan.appendChild(tanda);
                    kiri.appendChild(layanan);
                }

                var kanan = document.createElement('span');
                kanan.className = toko.stok > 0 ? 'cb-lencana cb-lencana--berhasil' : 'cb-lencana';
                kanan.textContent = toko.keterangan;

                baris.appendChild(kiri);
                baris.appendChild(kanan);
                hasil.appendChild(baris);
            });
        })
        .catch(function () {
            hasil.innerHTML = '<p class="cb-catatan">Stok toko gagal diambil. Silakan coba lagi.</p>';
        });
    });
});
</script>
@endpush
