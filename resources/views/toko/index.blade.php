@extends('layouts.app')

@section('judul', 'Daftar Toko')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li>Daftar Toko</li>
        </ol>
        <h1>Daftar Toko Colorbox</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Cari toko berdasarkan nama atau kota, lalu lihat jam buka dan layanan yang tersedia di sana.
        </p>
    </div>

    {{-- Pencarian nama atau kota dan penyaring layanan --}}
    <form action="{{ route('toko.index') }}" method="GET" class="cb-borang cb-bagian cb-bagian--rapat">
        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="cari-toko">Nama toko atau kota</label>
                <input class="cb-masukan" type="search" id="cari-toko" name="cari" value="{{ $cari }}"
                       placeholder="Misalnya Serpong">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="layanan">Layanan</label>
                <select class="cb-masukan" id="layanan" name="layanan">
                    <option value="">Semua layanan</option>
                    <option value="ttc" @if($layanan === 'ttc') selected @endif>Tap Try and Collect</option>
                    <option value="donasi" @if($layanan === 'donasi') selected @endif>Donasi Poin</option>
                </select>
            </div>
        </div>
        <div class="d-flex gap-3 flex-wrap mt-3">
            <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--kecil">Cari toko</button>
            <a class="cb-tombol cb-tombol--polos cb-tombol--kecil" href="{{ route('toko.index') }}">Bersihkan</a>
        </div>
    </form>

    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-kepala-bagian">
            <div>
                <h2 style="font-size: var(--cb-h3)">{{ $toko->count() }} toko ditemukan</h2>
                <p>Tersebar di {{ $kota->count() }} kota.</p>
            </div>
        </div>

        @forelse($toko as $satu)
            <div class="cb-toko">
                <div>
                    <div class="cb-toko__nama">
                        <a href="{{ route('toko.show', $satu) }}">{{ $satu->name }}</a>
                    </div>
                    <div class="cb-toko__ket">
                        {{ $satu->address }}, {{ $satu->city }}
                        @if($satu->floor) &middot; {{ $satu->floor }} @endif
                    </div>
                    <div class="cb-toko__ket">
                        Buka {{ jam_singkat($satu->open_time) }} – {{ jam_singkat($satu->close_time) }}
                        @if($satu->phone) &middot; {{ $satu->phone }} @endif
                    </div>
                    <div class="cb-toko__layanan">
                        <span class="cb-lencana">Kode {{ $satu->code }}</span>
                        @if($satu->has_ttc)
                            <span class="cb-lencana cb-lencana--berhasil">Tap Try and Collect</span>
                        @endif
                        @if($satu->has_donation)
                            <span class="cb-lencana cb-lencana--aksen">Donasi Poin</span>
                        @endif
                    </div>
                </div>
                <a class="cb-tombol cb-tombol--garis cb-tombol--kecil" href="{{ route('toko.show', $satu) }}">Lihat toko</a>
            </div>
        @empty
            <div class="cb-kosong">
                <h2>Toko tidak ditemukan</h2>
                <p>Coba kata kunci lain atau bersihkan penyaring layanan.</p>
                <a class="cb-tombol cb-tombol--utama" href="{{ route('toko.index') }}">Lihat semua toko</a>
            </div>
        @endforelse
    </section>

</div>
@endsection
