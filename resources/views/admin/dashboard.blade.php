@extends('layouts.admin')

@section('judul', 'Dashboard')

@section('isi')

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Ringkasan Hari Ini</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Angka di bawah ini dihitung langsung dari basis data, jadi selalu mengikuti keadaan terbaru.
    </p>

    <div class="ad-angka mt-3">
        <div>
            <p class="ad-angka__nilai mb-0">{{ $ringkasan['pesanan_hari_ini'] }}</p>
            <p class="ad-angka__label mb-0">Pesanan hari ini</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $ringkasan['menunggu_pembayaran'] }}</p>
            <p class="ad-angka__label mb-0">Menunggu pembayaran</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $ringkasan['perlu_diproses'] }}</p>
            <p class="ad-angka__label mb-0">Perlu diproses</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ rupiah($ringkasan['penjualan_bulan']) }}</p>
            <p class="ad-angka__label mb-0">Penjualan {{ tanggal_id($bulan) }} sampai kini</p>
        </div>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Barang dan Layanan</h2>

    <div class="ad-angka mt-3">
        <div>
            <p class="ad-angka__nilai mb-0">{{ $ringkasan['produk_aktif'] }}</p>
            <p class="ad-angka__label mb-0">Produk aktif</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $ringkasan['stok_menipis'] }}</p>
            <p class="ad-angka__label mb-0">Varian stok menipis</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $ringkasan['retur_menunggu'] }}</p>
            <p class="ad-angka__label mb-0">Retur menunggu validasi</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $ringkasan['donasi_menunggu'] }}</p>
            <p class="ad-angka__label mb-0">Donasi menunggu validasi</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $ringkasan['ttc_hari_ini'] }}</p>
            <p class="ad-angka__label mb-0">Booking Tap Try and Collect hari ini</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $ringkasan['keluhan_baru'] }}</p>
            <p class="ad-angka__label mb-0">Keluhan baru</p>
        </div>
    </div>
</div>

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 style="font-size: var(--cb-h4)" class="mb-0">Sepuluh Pesanan Terbaru</h2>
        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.pesanan.index') }}">Semua Pesanan</a>
    </div>

    <div class="ad-gulir mt-3">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Metode</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($pesananTerbaru as $pesanan)
                <tr>
                    <td><a href="{{ route('admin.pesanan.show', $pesanan) }}">{{ $pesanan->order_code }}</a></td>
                    <td>{{ $pesanan->pengguna?->name ?? '-' }}</td>
                    <td>{{ tanggal_id($pesanan->created_at) }}</td>
                    <td>{{ $pesanan->method === 'ambil_toko' ? 'Ambil di toko' : 'Dikirim kurir' }}</td>
                    <td>{{ rupiah($pesanan->total) }}</td>
                    <td><span class="cb-lencana cb-lencana--aksen">{{ $pesanan->labelStatus() }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada pesanan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 style="font-size: var(--cb-h4)" class="mb-0">Stok Paling Menipis</h2>
        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.produk.index') }}">Kelola Produk</a>
    </div>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Varian dengan stok kurang dari {{ $batasStok }} potong.
    </p>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Produk</th>
                <th>Warna</th>
                <th>Ukuran</th>
                <th>Stok</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($varianMenipis as $varian)
                <tr>
                    <td>{{ $varian->produk?->name ?? '-' }}</td>
                    <td>{{ $varian->color }}</td>
                    <td>{{ $varian->size }}</td>
                    <td><strong>{{ $varian->stock }}</strong></td>
                    <td>
                        @if($varian->produk)
                            <a class="cb-tombol cb-tombol--kecil cb-tombol--garis"
                               href="{{ route('admin.produk.show', $varian->produk) }}">Ubah Stok</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Semua stok masih aman.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
