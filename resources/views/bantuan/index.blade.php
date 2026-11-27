@extends('layouts.app')

@section('judul', 'Pusat Bantuan')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li>Bantuan</li>
        </ol>
        <h1>Pusat Bantuan</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Kumpulan keterangan mengenai belanja, pembayaran, pengiriman, retur, dan layanan Colorbox lainnya.
        </p>
    </div>

    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-papan">
            @foreach($topik as $alamat => $keterangan)
                <div class="cb-papan__butir">
                    <h3>{{ $keterangan[0] }}</h3>
                    <p>{{ $keterangan[1] }}</p>
                    <a class="cb-tautan-lanjut" href="{{ route('bantuan.show', $alamat) }}">Baca selengkapnya</a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-panel">
            <h3 style="font-size: var(--cb-h4)">Masih perlu bantuan?</h3>
            <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                Lacak pesanan Anda memakai kode pesanan, atau kirim keluhan lewat halaman akun supaya ditindaklanjuti petugas Colorbox.
            </p>
            <div class="d-flex gap-3 flex-wrap">
                <a class="cb-tombol cb-tombol--utama cb-tombol--kecil" href="{{ route('lacak.index') }}">Lacak Pesanan</a>
                <a class="cb-tombol cb-tombol--garis cb-tombol--kecil" href="{{ route('keluhan.index') }}">Kirim Keluhan</a>
            </div>
        </div>
    </section>

</div>
@endsection
