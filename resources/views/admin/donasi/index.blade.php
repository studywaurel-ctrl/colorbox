@extends('layouts.admin')

@section('judul', 'Donasi')

@section('isi')

<div class="ad-kartu">
    <div>
        <h2 style="font-size: var(--cb-h4)" class="mb-1">Donasi Pakaian</h2>
        <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
            {{ $daftar->total() }} donasi tercatat pada penyaringan ini.
        </p>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.donasi.index') }}" method="GET">
        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="status">Status</label>
                <select class="cb-masukan" id="status" name="status">
                    <option value="">Semua status</option>
                    @foreach($daftarStatus as $pilihan)
                        <option value="{{ $pilihan }}" @selected($status === $pilihan)>{{ ucfirst(str_replace('_', ' ', $pilihan)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="toko">Toko</label>
                <select class="cb-masukan" id="toko" name="toko">
                    <option value="">Semua toko</option>
                    @foreach($daftarToko as $toko)
                        <option value="{{ $toko->id }}" @selected((string) $tokoId === (string) $toko->id)>{{ $toko->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-2">
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.donasi.index') }}">Bersihkan</a>
        </div>
    </form>
</div>

<div class="ad-kartu">
    <div class="ad-gulir">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Pendonor</th>
                <th>Toko</th>
                <th>Jadwal</th>
                <th>Perkiraan</th>
                <th>Diterima</th>
                <th>Poin</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $donasi)
                <tr>
                    <td>{{ $donasi->donation_code }}</td>
                    <td>{{ $donasi->pengguna?->name ?? '-' }}</td>
                    <td>{{ $donasi->toko?->name ?? '-' }}</td>
                    <td>
                        @if($donasi->slot)
                            {{ tanggal_id($donasi->slot->date) }}<br>
                            <span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">
                                {{ jam_singkat($donasi->slot->start_time) }}–{{ jam_singkat($donasi->slot->end_time) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $donasi->estimated_items }} potong</td>
                    <td>{{ $donasi->received_items !== null ? $donasi->received_items.' potong' : '-' }}</td>
                    <td>{{ $donasi->points_awarded ?: 0 }}</td>
                    <td><span class="cb-lencana cb-lencana--aksen">{{ ucfirst(str_replace('_', ' ', $donasi->status)) }}</span></td>
                    <td>
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.donasi.show', $donasi) }}">Rincian</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9">Belum ada donasi pada penyaringan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
