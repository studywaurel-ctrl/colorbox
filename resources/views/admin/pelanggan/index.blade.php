@extends('layouts.admin')

@section('judul', 'Pelanggan')

@section('isi')

<div class="ad-kartu">
    <div>
        <h2 style="font-size: var(--cb-h4)" class="mb-1">Daftar Pelanggan</h2>
        <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
            {{ $daftar->total() }} pelanggan tercatat. Halaman ini hanya untuk melihat data.
        </p>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.pelanggan.index') }}" method="GET">
        <div class="d-flex gap-2 flex-wrap align-items-end">
            <div class="cb-isian mb-0">
                <label class="cb-label" for="cari">Cari nama, email, atau nomor telepon</label>
                <input class="cb-masukan" type="search" id="cari" name="cari" value="{{ $cari }}">
            </div>
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.pelanggan.index') }}">Bersihkan</a>
        </div>
    </form>
</div>

<div class="ad-kartu">
    <div class="ad-gulir">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Level</th>
                <th>Poin</th>
                <th>Pesanan</th>
                <th>Bergabung</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $pelanggan)
                <tr>
                    <td>{{ $pelanggan->name }}</td>
                    <td>{{ $pelanggan->email }}</td>
                    <td>{{ $pelanggan->phone ?: '-' }}</td>
                    <td>{{ $pelanggan->member_level ?: '-' }}</td>
                    <td>{{ $pelanggan->points }}</td>
                    <td>{{ $pelanggan->pesanan_count }}</td>
                    <td>{{ tanggal_id($pelanggan->created_at) }}</td>
                    <td>
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.pelanggan.show', $pelanggan) }}">Rincian</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Pelanggan tidak ditemukan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
