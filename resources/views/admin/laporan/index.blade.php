@extends('layouts.admin')

@section('judul', 'Laporan Penjualan')

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">Laporan Penjualan</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                Periode {{ tanggal_id($dari) }} sampai {{ tanggal_id($sampai) }}.
                Yang dihitung hanya pesanan yang uangnya sudah masuk; pesanan yang masih
                menunggu pembayaran dan yang dibatalkan tidak ikut dihitung.
            </p>
        </div>
        <a class="cb-tombol cb-tombol--garis cb-tombol--kecil"
           href="{{ route('admin.laporan.index', ['dari' => $dari, 'sampai' => $sampai, 'format' => 'csv']) }}">
            <i class="bi bi-download"></i> Unduh CSV
        </a>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.laporan.index') }}" method="GET">
        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="dari">Tanggal mulai</label>
                <input class="cb-masukan" type="date" id="dari" name="dari" value="{{ $dari }}">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="sampai">Tanggal akhir</label>
                <input class="cb-masukan" type="date" id="sampai" name="sampai" value="{{ $sampai }}">
            </div>
        </div>
        <div class="d-flex gap-2 mt-2">
            <button class="cb-tombol cb-tombol--kecil" type="submit">Tampilkan</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.laporan.index') }}">Bulan Berjalan</a>
        </div>
    </form>

    <div class="ad-angka mt-3">
        <div>
            <p class="ad-angka__nilai mb-0">{{ $jumlahPesanan }}</p>
            <p class="ad-angka__label mb-0">Jumlah pesanan</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $produkTerjual }}</p>
            <p class="ad-angka__label mb-0">Produk terjual (potong)</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ rupiah($totalPenjualan) }}</p>
            <p class="ad-angka__label mb-0">Total penjualan</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ rupiah($rataRata) }}</p>
            <p class="ad-angka__label mb-0">Rata-rata nilai pesanan</p>
        </div>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Penjualan per Hari</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jumlah Pesanan</th>
                <th>Nilai Penjualan</th>
            </tr>
            </thead>
            <tbody>
            @forelse($perHari as $baris)
                <tr>
                    <td>{{ tanggal_id($baris->tanggal, true) }}</td>
                    <td>{{ $baris->jumlah }}</td>
                    <td>{{ rupiah($baris->nilai) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Belum ada penjualan pada periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Sepuluh Produk Terlaris</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah Terjual</th>
                <th>Nilai Penjualan</th>
            </tr>
            </thead>
            <tbody>
            @forelse($terlaris as $baris)
                <tr>
                    <td>{{ $baris->product_name }}</td>
                    <td>{{ $baris->jumlah }}</td>
                    <td>{{ rupiah($baris->nilai) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Belum ada produk terjual pada periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Metode Penerimaan Barang</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Metode</th>
                <th>Jumlah Pesanan</th>
                <th>Nilai Penjualan</th>
            </tr>
            </thead>
            <tbody>
            @forelse($perMetode as $baris)
                <tr>
                    <td>{{ $baris->method === 'ambil_toko' ? 'Ambil di toko' : 'Dikirim kurir' }}</td>
                    <td>{{ $baris->jumlah }}</td>
                    <td>{{ rupiah($baris->nilai) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Belum ada data pada periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
