@extends('layouts.app')

@section('judul', 'QR Meja Kasir '.$toko->name)

@push('gaya')
<style>
    /* Lembar ini dicetak lalu diletakkan di meja kasir toko */
    .qr-lembar { max-width: 520px; margin-inline: auto; text-align: center; }
    .qr-kode { font-size: 3.4rem; font-weight: 700; letter-spacing: 0.14em; margin-block: var(--cb-s4); }
    .qr-gambar { display: flex; justify-content: center; min-height: 220px; }
    .qr-gambar svg, .qr-gambar img { width: 220px; height: 220px; }
</style>
@endpush

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li><a href="{{ route('toko.index') }}">Daftar Toko</a></li>
            <li><a href="{{ route('toko.show', $toko) }}">{{ $toko->name }}</a></li>
            <li>QR Meja Kasir</li>
        </ol>
        <h1>QR Meja Kasir</h1>
    </div>

    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-panel qr-lembar">
            <p class="cb-lencana cb-lencana--aksen">Colorbox</p>
            <h2 style="font-size: var(--cb-h3)">{{ $toko->name }}</h2>
            <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                {{ $toko->address }}, {{ $toko->city }}
            </p>

            {{-- Kode toko ditulis besar supaya tetap terbaca bila QR gagal dipindai --}}
            <p class="qr-kode">{{ $toko->code }}</p>

            {{-- Kode yang digambar menjadi QR dibaca dari atribut data-kode --}}
            <div class="qr-gambar" id="gambar-qr" data-kode="{{ $toko->code }}"></div>

            <p class="mt-4 mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                Pindai kode ini di meja kasir ketika menyerahkan donasi pakaian atau mengambil pesanan Tap Try and Collect.
            </p>
        </div>
    </section>

    <div class="d-flex gap-3 flex-wrap">
        <a class="cb-tombol cb-tombol--garis cb-tombol--kecil" href="{{ route('toko.show', $toko) }}">Kembali ke halaman toko</a>
    </div>

</div>
@endsection

@push('skrip')
<script src="{{ asset('vendor/qrcode/qrcode.js') }}"></script>
<script>
// Menggambar QR dari kode toko yang disimpan pada atribut data-kode.
document.addEventListener('DOMContentLoaded', function () {
    var wadah = document.getElementById('gambar-qr');
    if (!wadah) { return; }

    var kode = wadah.dataset.kode;
    if (!kode) { return; }

    var qr = qrcode(0, 'M');
    qr.addData(kode);
    qr.make();
    wadah.innerHTML = qr.createSvgTag(6, 2);
});
</script>
@endpush
