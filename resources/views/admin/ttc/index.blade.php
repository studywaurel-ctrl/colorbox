@extends('layouts.admin')

@section('judul', 'Tap Try and Collect')

@section('isi')

<div class="ad-kartu">
    <div>
        <h2 style="font-size: var(--cb-h4)" class="mb-1">Booking Tap Try and Collect</h2>
        <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
            {{ $daftar->total() }} booking tercatat pada penyaringan ini.
        </p>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.ttc.index') }}" method="GET">
        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="status">Status</label>
                <select class="cb-masukan" id="status" name="status">
                    <option value="">Semua status</option>
                    @foreach($daftarStatus as $pilihan)
                        <option value="{{ $pilihan }}" @selected($status === $pilihan)>{{ ucfirst($pilihan) }}</option>
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
        <div class="cb-isian">
            <label class="cb-label" for="tanggal">Tanggal kunjungan</label>
            <input class="cb-masukan" type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}" style="max-width: 240px">
        </div>
        <div class="d-flex gap-2 mt-2">
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.ttc.index') }}">Bersihkan</a>
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
                <th>Toko</th>
                <th>Jadwal</th>
                <th>Kode Pembelian</th>
                <th>Total Dibayar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $booking)
                <tr>
                    <td>{{ $booking->booking_code }}</td>
                    <td>{{ $booking->pengguna?->name ?? '-' }}</td>
                    <td>{{ $booking->toko?->name ?? '-' }}</td>
                    <td>
                        @if($booking->slot)
                            {{ tanggal_id($booking->slot->date) }}<br>
                            <span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">
                                {{ jam_singkat($booking->slot->start_time) }}–{{ jam_singkat($booking->slot->end_time) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $booking->purchase_code ?: '-' }}</td>
                    <td>{{ $booking->total_paid ? rupiah($booking->total_paid) : '-' }}</td>
                    <td><span class="cb-lencana cb-lencana--aksen">{{ ucfirst($booking->status) }}</span></td>
                    <td>
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.ttc.show', $booking) }}">Rincian</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Belum ada booking pada penyaringan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
