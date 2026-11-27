@extends('layouts.app')

@section('judul', 'Beranda')

@section('isi')

    {{-- Bagian sorotan di paling atas halaman --}}
    <section class="cb-sorot">
        <div class="cb-wadah">
            <div class="cb-sorot__isi">
                <p class="cb-lencana cb-lencana--aksen mb-3">Koleksi Colorbox</p>
                <h1>Temukan Gaya Favoritmu di Colorbox</h1>
                <p>Eksplor koleksi fashion terbaru yang siap melengkapi gaya sehari-harimu.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="cb-tombol cb-tombol--utama" href="{{ route('produk.index') }}">Belanja Sekarang</a>
                    <a class="cb-tombol cb-tombol--garis" href="{{ route('produk.baru') }}">Lihat Koleksi</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk yang baru masuk --}}
    <section class="cb-bagian">
        <div class="cb-wadah">
            <div class="cb-kepala-bagian">
                <div>
                    <h2>New Arrival</h2>
                    <p>Koleksi yang baru saja masuk ke katalog Colorbox.</p>
                </div>
                <a class="cb-tautan-lanjut" href="{{ route('produk.baru') }}">Lihat semua</a>
            </div>

            @if($produkBaru->count() > 0)
                <div class="cb-kisi">
                    @foreach($produkBaru as $produk)
                        <x-kartu-produk :produk="$produk" />
                    @endforeach
                </div>
            @else
                <p class="cb-catatan">Belum ada produk baru untuk ditampilkan.</p>
            @endif
        </div>
    </section>

    {{-- Kategori pilihan --}}
    @if($kategoriPilihan->count() > 0)
        <section class="cb-bagian cb-bagian--lembut">
            <div class="cb-wadah">
                <div class="cb-kepala-bagian">
                    <div>
                        <h2>Kategori Pilihan</h2>
                        <p>Mulai dari kategori yang paling sering dicari pelanggan.</p>
                    </div>
                    <a class="cb-tautan-lanjut" href="{{ route('produk.index') }}">Semua produk</a>
                </div>

                <div class="cb-saring__kiri">
                    @foreach($kategoriPilihan as $kategori)
                        <a class="cb-cip" href="{{ route('produk.index', ['kategori' => $kategori->slug]) }}">{{ $kategori->name }}</a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Layanan Colorbox --}}
    <section class="cb-bagian">
        <div class="cb-wadah">
            <div class="cb-kepala-bagian">
                <div>
                    <h2>Layanan Colorbox</h2>
                    <p>Layanan yang menghubungkan belanja lewat situs dengan toko Colorbox.</p>
                </div>
            </div>

            <div class="cb-papan">
                <div class="cb-papan__butir">
                    <h3>Virtual Try On</h3>
                    <p>Lihat gambaran pakaian sebelum Anda memutuskan untuk membelinya.</p>
                    <a class="cb-tautan-lanjut" href="{{ route('vto.index') }}">Mulai mencoba</a>
                </div>
                <div class="cb-papan__butir">
                    <h3>Tap Try and Collect</h3>
                    <p>Pesan lewat situs, coba dan ambil barangnya di toko pilihan Anda tanpa ongkos kirim.</p>
                    <a class="cb-tautan-lanjut" href="{{ route('ttc.index') }}">Cara kerjanya</a>
                </div>
                <div class="cb-papan__butir">
                    <h3>Donasi Poin</h3>
                    <p>Serahkan pakaian layak pakai di toko dan tukarkan poinnya menjadi potongan belanja.</p>
                    <a class="cb-tautan-lanjut" href="{{ route('donasi.index') }}">Jadwalkan donasi</a>
                </div>
                <div class="cb-papan__butir">
                    <h3>Daftar Toko</h3>
                    <p>Cari alamat, jam buka, dan layanan yang tersedia di setiap toko Colorbox.</p>
                    <a class="cb-tautan-lanjut" href="{{ route('toko.index') }}">Lihat daftar toko</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Koleksi kolaborasi --}}
    @if($produkKolaborasi->count() > 0)
        <section class="cb-bagian cb-bagian--lembut">
            <div class="cb-wadah">
                <div class="cb-kepala-bagian">
                    <div>
                        <h2>Koleksi Kolaborasi</h2>
                        <p>
                            @if($namaKoleksi->count() > 0)
                                Koleksi khusus hasil kerja sama: {{ $namaKoleksi->implode(', ') }}.
                            @else
                                Koleksi khusus hasil kerja sama Colorbox.
                            @endif
                        </p>
                    </div>
                    @if($namaKoleksi->count() > 0)
                        <a class="cb-tautan-lanjut" href="{{ route('produk.index', ['koleksi' => $namaKoleksi->first()]) }}">Lihat koleksinya</a>
                    @endif
                </div>

                <div class="cb-kisi">
                    @foreach($produkKolaborasi as $produk)
                        <x-kartu-produk :produk="$produk" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
