@extends('layouts.admin')

@section('judul', 'Pesanan')

@section('isi')

<div class="ad-kartu">
    <div>
        <h2 style="font-size: var(--cb-h4)" class="mb-1">Pesanan Pelanggan</h2>
        <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
            {{ $daftar->total() }} pesanan tercatat pada penyaringan ini.
        </p>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.pesanan.index') }}" method="GET">
        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="cari">Cari kode pesanan, nama, atau email</label>
                <input class="cb-masukan" type="search" id="cari" name="cari" value="{{ $cari }}" placeholder="Misalnya: CBX-20260101-0001">
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="status">Status</label>
                <select class="cb-masukan" id="status" name="status">
                    <option value="">Semua status</option>
                    @foreach($daftarStatus as $pilihan)
                        <option value="{{ $pilihan }}" @selected($status === $pilihan)>{{ ucfirst(str_replace('_', ' ', $pilihan)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-2">
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.pesanan.index') }}">Bersihkan</a>
        </div>
    </form>
</div>

<div class="ad-kartu">
    <div class="ad-gulir">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Metode</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $pesanan)
                <tr>
                    <td>{{ $pesanan->order_code }}</td>
                    <td>
                        {{ $pesanan->pengguna?->name ?? '-' }}<br>
                        <span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">{{ $pesanan->pengguna?->email ?? '-' }}</span>
                    </td>
                    <td>{{ tanggal_id($pesanan->created_at) }}</td>
                    <td>{{ $pesanan->method === 'ambil_toko' ? 'Ambil di toko' : 'Dikirim kurir' }}</td>
                    <td>{{ rupiah($pesanan->total) }}</td>
                    <td>
                        @if($pesanan->pembayaran)
                            <span class="cb-lencana {{ $pesanan->pembayaran->payment_status === 'lunas' ? 'cb-lencana--berhasil' : 'cb-lencana--ingat' }}">
                                {{ ucfirst($pesanan->pembayaran->payment_status) }}
                            </span>
                        @else
                            <span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">Belum ada</span>
                        @endif
                    </td>
                    <td><span class="cb-lencana cb-lencana--aksen">{{ $pesanan->labelStatus() }}</span></td>
                    <td>
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.pesanan.show', $pesanan) }}">Rincian</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Belum ada pesanan pada penyaringan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
