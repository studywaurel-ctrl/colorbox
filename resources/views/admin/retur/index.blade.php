@extends('layouts.admin')

@section('judul', 'Retur')

@section('isi')

<div class="ad-kartu">
    <div>
        <h2 style="font-size: var(--cb-h4)" class="mb-1">Pengajuan Retur dan Penukaran</h2>
        <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
            {{ $daftar->total() }} pengajuan tercatat pada penyaringan ini.
        </p>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.retur.index') }}" method="GET">
        <div class="d-flex gap-2 flex-wrap align-items-end">
            <div class="cb-isian mb-0">
                <label class="cb-label" for="status">Status</label>
                <select class="cb-masukan" id="status" name="status">
                    <option value="">Semua status</option>
                    @foreach($daftarStatus as $pilihan)
                        <option value="{{ $pilihan }}" @selected($status === $pilihan)>{{ ucfirst(str_replace('_', ' ', $pilihan)) }}</option>
                    @endforeach
                </select>
            </div>
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.retur.index') }}">Bersihkan</a>
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
                <th>Pesanan</th>
                <th>Jenis</th>
                <th>Cara</th>
                <th>Toko</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $retur)
                <tr>
                    <td>{{ $retur->return_code }}</td>
                    <td>{{ $retur->pengguna?->name ?? '-' }}</td>
                    <td>{{ $retur->pesanan?->order_code ?? ($retur->purchase_code ?: '-') }}</td>
                    <td>{{ $retur->type === 'tukar' ? 'Tukar barang' : 'Pengembalian dana' }}</td>
                    <td>{{ $retur->method === 'toko' ? 'Dibawa ke toko' : 'Dikirim' }}</td>
                    <td>{{ $retur->toko?->name ?? '-' }}</td>
                    <td>{{ tanggal_id($retur->created_at) }}</td>
                    <td><span class="cb-lencana cb-lencana--aksen">{{ ucfirst(str_replace('_', ' ', $retur->status)) }}</span></td>
                    <td>
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.retur.show', $retur) }}">Rincian</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9">Belum ada pengajuan retur pada penyaringan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
