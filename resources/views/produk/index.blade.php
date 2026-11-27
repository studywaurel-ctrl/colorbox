@extends('layouts.app')

@section('judul', $hanyaBaru ? 'New Arrival' : 'Semua Produk')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li>{{ $hanyaBaru ? 'New Arrival' : 'Semua Produk' }}</li>
        </ol>
        <h1>
            @if($kategoriTerpilih)
                {{ $kategoriTerpilih->name }}
            @else
                {{ $hanyaBaru ? 'New Arrival' : 'Semua Produk' }}
            @endif
        </h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            {{ $produk->total() }} produk ditemukan.
            @if($hanyaBaru) Halaman ini hanya menampilkan koleksi yang baru masuk. @endif
        </p>
    </div>

    {{-- Baris penyaring: tombol laci, penanda saringan yang sedang dipakai, dan pengurutan --}}
    <div class="cb-saring">
        <div class="cb-saring__kiri">
            <button type="button" class="cb-cip" id="buka-saring" aria-expanded="false">
                <i class="bi bi-sliders"></i> Saring
            </button>

            @if(request('cari'))
                <a class="cb-cip aktif" href="{{ request()->fullUrlWithoutQuery(['cari', 'page']) }}">
                    "{{ request('cari') }}" <i class="bi bi-x-lg"></i>
                </a>
            @endif
            @if($kategoriTerpilih)
                <a class="cb-cip aktif" href="{{ request()->fullUrlWithoutQuery(['kategori', 'page']) }}">
                    {{ $kategoriTerpilih->name }} <i class="bi bi-x-lg"></i>
                </a>
            @endif
            @if(request('koleksi'))
                <a class="cb-cip aktif" href="{{ request()->fullUrlWithoutQuery(['koleksi', 'page']) }}">
                    {{ request('koleksi') }} <i class="bi bi-x-lg"></i>
                </a>
            @endif
            @if(request('warna'))
                <a class="cb-cip aktif" href="{{ request()->fullUrlWithoutQuery(['warna', 'page']) }}">
                    {{ request('warna') }} <i class="bi bi-x-lg"></i>
                </a>
            @endif
            @if(request('ukuran'))
                <a class="cb-cip aktif" href="{{ request()->fullUrlWithoutQuery(['ukuran', 'page']) }}">
                    Ukuran {{ request('ukuran') }} <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>

        <form action="{{ $hanyaBaru ? route('produk.baru') : route('produk.index') }}" method="GET"
              class="d-flex align-items-center gap-2">
            {{-- saringan lain ikut dikirim supaya tidak hilang ketika urutan diubah --}}
            <input type="hidden" name="cari" value="{{ request('cari') }}">
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            <input type="hidden" name="koleksi" value="{{ request('koleksi') }}">
            <input type="hidden" name="warna" value="{{ request('warna') }}">
            <input type="hidden" name="ukuran" value="{{ request('ukuran') }}">
            <input type="hidden" name="harga_min" value="{{ request('harga_min') }}">
            <input type="hidden" name="harga_max" value="{{ request('harga_max') }}">

            <label class="cb-sr" for="urut">Urutkan</label>
            <select class="cb-masukan" id="urut" name="urut" style="min-height: 38px; width: auto"
                    onchange="this.form.submit()">
                <option value="terbaru" @if($urut === 'terbaru') selected @endif>Terbaru</option>
                <option value="termurah" @if($urut === 'termurah') selected @endif>Harga terendah</option>
                <option value="termahal" @if($urut === 'termahal') selected @endif>Harga tertinggi</option>
                <option value="nama" @if($urut === 'nama') selected @endif>Nama A – Z</option>
            </select>
            <noscript><button type="submit" class="cb-tombol cb-tombol--kecil cb-tombol--garis">Urutkan</button></noscript>
        </form>
    </div>

    {{-- Laci penyaring, dibuka dan ditutup oleh app.js --}}
    <div class="cb-laci" id="laci-saring">
        <form action="{{ $hanyaBaru ? route('produk.baru') : route('produk.index') }}" method="GET" class="cb-borang">
            <input type="hidden" name="urut" value="{{ $urut }}">

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="cb-isian">
                        <label class="cb-label" for="f-cari">Kata kunci</label>
                        <input class="cb-masukan" type="search" id="f-cari" name="cari" value="{{ request('cari') }}"
                               placeholder="Misalnya kemeja linen">
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="cb-isian">
                        <label class="cb-label" for="f-kategori">Kategori</label>
                        <select class="cb-masukan" id="f-kategori" name="kategori">
                            <option value="">Semua kategori</option>
                            @foreach($kategoriInduk as $induk)
                                <option value="{{ $induk->slug }}" @if(request('kategori') === $induk->slug) selected @endif>{{ $induk->name }}</option>
                                @foreach($induk->anak->sortBy('sort_order') as $anak)
                                    <option value="{{ $anak->slug }}" @if(request('kategori') === $anak->slug) selected @endif>&nbsp;&nbsp;{{ $anak->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="cb-isian">
                        <label class="cb-label" for="f-koleksi">Koleksi</label>
                        <select class="cb-masukan" id="f-koleksi" name="koleksi">
                            <option value="">Semua koleksi</option>
                            @foreach($daftarKoleksi as $koleksi)
                                <option value="{{ $koleksi }}" @if(request('koleksi') === $koleksi) selected @endif>{{ $koleksi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="cb-isian">
                        <label class="cb-label" for="f-warna">Warna</label>
                        <select class="cb-masukan" id="f-warna" name="warna">
                            <option value="">Semua warna</option>
                            @foreach($daftarWarna as $warna)
                                <option value="{{ $warna }}" @if(request('warna') === $warna) selected @endif>{{ $warna }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="cb-isian">
                        <label class="cb-label" for="f-ukuran">Ukuran tersedia</label>
                        <select class="cb-masukan" id="f-ukuran" name="ukuran">
                            <option value="">Semua ukuran</option>
                            @foreach($daftarUkuran as $ukuran)
                                <option value="{{ $ukuran }}" @if(request('ukuran') === $ukuran) selected @endif>{{ $ukuran }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6">
                    <div class="cb-baris-isian cb-baris-isian--dua">
                        <div class="cb-isian">
                            <label class="cb-label" for="f-harga-min">Harga dari</label>
                            <input class="cb-masukan" type="number" id="f-harga-min" name="harga_min" min="0"
                                   value="{{ request('harga_min') }}" placeholder="0">
                        </div>
                        <div class="cb-isian">
                            <label class="cb-label" for="f-harga-max">Harga sampai</label>
                            <input class="cb-masukan" type="number" id="f-harga-max" name="harga_max" min="0"
                                   value="{{ request('harga_max') }}" placeholder="500000">
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex gap-3 flex-wrap">
                    <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--kecil">Terapkan saringan</button>
                    <a class="cb-tombol cb-tombol--polos cb-tombol--kecil"
                       href="{{ $hanyaBaru ? route('produk.baru') : route('produk.index') }}">Bersihkan</a>
                </div>
            </div>
        </form>
    </div>

    <section class="cb-bagian cb-bagian--rapat">
        @if($produk->count() > 0)
            <div class="cb-kisi">
                @foreach($produk as $satu)
                    <x-kartu-produk :produk="$satu" />
                @endforeach
            </div>

            <div class="mt-5">
                {{ $produk->links() }}
            </div>
        @else
            <div class="cb-kosong">
                <h2>Produk tidak ditemukan</h2>
                <p>Coba ubah kata kunci atau bersihkan saringan yang sedang dipakai.</p>
                <a class="cb-tombol cb-tombol--utama" href="{{ route('produk.index') }}">Lihat semua produk</a>
            </div>
        @endif
    </section>

</div>
@endsection
