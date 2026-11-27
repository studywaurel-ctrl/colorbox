@extends('layouts.app')

@section('judul', $toko->name)

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li><a href="{{ route('toko.index') }}">Daftar Toko</a></li>
            <li>{{ $toko->name }}</li>
        </ol>
        <h1>{{ $toko->name }}</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            {{ $toko->address }}, {{ $toko->city }}@if($toko->floor), {{ $toko->floor }}@endif.
        </p>
    </div>

    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-kisi cb-kisi--tiga">

            <div class="cb-panel">
                <h3 style="font-size: var(--cb-h4)">Jam buka</h3>
                <p class="mb-2" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                    Setiap hari, {{ jam_singkat($toko->open_time) }} – {{ jam_singkat($toko->close_time) }}.
                </p>
                <p class="mb-0" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                    Jam buka dapat menyesuaikan jam operasional pusat perbelanjaan.
                </p>
            </div>

            <div class="cb-panel">
                <h3 style="font-size: var(--cb-h4)">Keterangan toko</h3>
                <ul class="cb-rincian-biaya">
                    <li><span>Kode toko</span><span>{{ $toko->code }}</span></li>
                    <li><span>Kota</span><span>{{ $toko->city }}</span></li>
                    @if($toko->floor)
                        <li><span>Lantai dan unit</span><span>{{ $toko->floor }}</span></li>
                    @endif
                    @if($toko->phone)
                        <li><span>Telepon</span><span>{{ $toko->phone }}</span></li>
                    @endif
                </ul>
            </div>

            <div class="cb-panel">
                <h3 style="font-size: var(--cb-h4)">Layanan</h3>
                <div class="cb-toko__layanan mb-3">
                    @if($toko->has_ttc)
                        <span class="cb-lencana cb-lencana--berhasil">Tap Try and Collect</span>
                    @endif
                    @if($toko->has_donation)
                        <span class="cb-lencana cb-lencana--aksen">Donasi Poin</span>
                    @endif
                    @if(! $toko->has_ttc && ! $toko->has_donation)
                        <span class="cb-lencana">Belanja di toko</span>
                    @endif
                </div>

                <div class="d-grid gap-2">
                    @if($toko->has_ttc)
                        <a class="cb-tombol cb-tombol--utama cb-tombol--kecil" href="{{ route('ttc.index') }}">
                            Booking Tap Try and Collect
                        </a>
                    @endif
                    @if($toko->has_donation)
                        <a class="cb-tombol cb-tombol--garis cb-tombol--kecil" href="{{ route('donasi.index') }}">
                            Jadwalkan donasi pakaian
                        </a>
                    @endif
                    <a class="cb-tombol cb-tombol--polos cb-tombol--kecil" href="{{ route('toko.qr', $toko) }}">
                        Lembar QR meja kasir
                    </a>
                </div>
            </div>

        </div>
    </section>

    {{-- Contoh produk yang stoknya masih ada di toko ini --}}
    @if($produk->count() > 0)
        <section class="cb-bagian">
            <div class="cb-kepala-bagian">
                <div>
                    <h2 style="font-size: var(--cb-h3)">Tersedia di toko ini</h2>
                    <p>Sebagian produk yang stoknya masih ada di {{ $toko->name }}.</p>
                </div>
                <a class="cb-tautan-lanjut" href="{{ route('produk.index') }}">Semua produk</a>
            </div>
            <div class="cb-kisi">
                @foreach($produk as $satu)
                    <x-kartu-produk :produk="$satu" />
                @endforeach
            </div>
        </section>
    @else
        <p class="cb-catatan">Data stok produk untuk toko ini belum tersedia.</p>
    @endif

</div>
@endsection
