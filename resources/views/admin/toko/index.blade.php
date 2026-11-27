@extends('layouts.admin')

@section('judul', 'Toko')

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">Daftar Toko</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                {{ $daftar->total() }} toko tercatat.
            </p>
        </div>
        <a class="cb-tombol cb-tombol--utama cb-tombol--kecil" href="{{ route('admin.toko.create') }}">
            <i class="bi bi-plus-lg"></i> Tambah Toko
        </a>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.toko.index') }}" method="GET">
        <div class="d-flex gap-2 flex-wrap align-items-end">
            <div class="cb-isian mb-0">
                <label class="cb-label" for="cari">Cari nama, kota, atau kode</label>
                <input class="cb-masukan" type="search" id="cari" name="cari" value="{{ $cari }}">
            </div>
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.toko.index') }}">Bersihkan</a>
        </div>
    </form>
</div>

<div class="ad-kartu">
    <div class="ad-gulir">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kota</th>
                <th>Jam</th>
                <th>Layanan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $toko)
                <tr>
                    <td>{{ $toko->code }}</td>
                    <td>
                        {{ $toko->name }}<br>
                        <span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">
                            {{ $toko->address }}{{ $toko->floor ? ', '.$toko->floor : '' }}
                        </span>
                    </td>
                    <td>{{ $toko->city }}</td>
                    <td>{{ jam_singkat($toko->open_time) }}–{{ jam_singkat($toko->close_time) }}</td>
                    <td>
                        @if($toko->has_ttc)<span class="cb-lencana cb-lencana--aksen">Tap Try and Collect</span>@endif
                        @if($toko->has_donation)<span class="cb-lencana cb-lencana--aksen">Donasi</span>@endif
                        @if(! $toko->has_ttc && ! $toko->has_donation)-@endif
                    </td>
                    <td>
                        @if($toko->is_active)
                            <span class="cb-lencana cb-lencana--berhasil">Aktif</span>
                        @else
                            <span class="cb-lencana cb-lencana--ingat">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2 flex-wrap">
                            <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.toko.edit', $toko) }}">Ubah</a>
                            <form action="{{ route('admin.toko.destroy', $toko) }}" method="POST"
                                  data-konfirmasi="Hapus toko {{ $toko->name }}?">
                                @csrf
                                @method('DELETE')
                                <button class="cb-tombol cb-tombol--kecil cb-tombol--polos" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Toko tidak ditemukan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
