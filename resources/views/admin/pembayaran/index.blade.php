@extends('layouts.admin')

@section('judul', 'Pembayaran')

@section('isi')

<div class="ad-kartu">
    <div>
        <h2 style="font-size: var(--cb-h4)" class="mb-1">Pembayaran Pesanan</h2>
        <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
            Status pembayaran hanya berubah mengikuti jawaban Midtrans. Admin tidak pernah
            mengubahnya dengan tangan, cukup menanyakan ulang status terakhirnya.
        </p>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.pembayaran.index') }}" method="GET">
        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="cari">Cari kode pesanan</label>
                <input class="cb-masukan" type="search" id="cari" name="cari" value="{{ $cari }}" placeholder="Misalnya: CBX-20260101-0001">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="status">Status pembayaran</label>
                <select class="cb-masukan" id="status" name="status">
                    <option value="">Semua status</option>
                    @foreach($daftarStatus as $pilihan)
                        <option value="{{ $pilihan }}" @selected($status === $pilihan)>{{ ucfirst($pilihan) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-2">
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.pembayaran.index') }}">Bersihkan</a>
        </div>
    </form>
</div>

<div class="ad-kartu">
    <div class="ad-gulir">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Pesanan</th>
                <th>Pelanggan</th>
                <th>Cara Bayar</th>
                <th>Nilai</th>
                <th>Status Midtrans</th>
                <th>Status Colorbox</th>
                <th>Dibayar</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $bayar)
                <tr>
                    <td>
                        @if($bayar->pesanan)
                            <a href="{{ route('admin.pesanan.show', $bayar->pesanan) }}">{{ $bayar->pesanan->order_code }}</a>
                        @else
                            <span style="color: var(--cb-tinta-samar)">Pesanan sudah tidak ada</span>
                        @endif
                    </td>
                    <td>{{ $bayar->pesanan?->pengguna?->name ?? '-' }}</td>
                    <td>{{ $bayar->payment_method ?: '-' }}</td>
                    <td>{{ rupiah($bayar->gross_amount) }}</td>
                    <td>{{ $bayar->transaction_status ?: '-' }}</td>
                    <td>
                        <span class="cb-lencana {{ $bayar->payment_status === 'lunas' ? 'cb-lencana--berhasil' : 'cb-lencana--ingat' }}">
                            {{ ucfirst($bayar->payment_status) }}
                        </span>
                    </td>
                    <td>{{ $bayar->paid_at ? tanggal_id($bayar->paid_at) : 'Belum' }}</td>
                    <td>
                        <form action="{{ route('admin.pembayaran.periksa', $bayar) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button class="cb-tombol cb-tombol--kecil cb-tombol--garis" type="submit">Periksa Status ke Midtrans</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Belum ada pembayaran pada penyaringan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
