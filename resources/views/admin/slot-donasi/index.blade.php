@extends('layouts.admin')

@section('judul', 'Slot Donasi')

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">Slot Donasi Pakaian</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                Jadwal ini hanya dipakai layanan donasi, terpisah dari slot Tap Try and Collect.
            </p>
        </div>
        <a class="cb-tombol cb-tombol--utama cb-tombol--kecil" href="{{ route('admin.slot-donasi.create') }}">
            <i class="bi bi-plus-lg"></i> Buat Slot
        </a>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.slot-donasi.index') }}" method="GET">
        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="toko">Toko</label>
                <select class="cb-masukan" id="toko" name="toko">
                    <option value="">Semua toko</option>
                    @foreach($daftarToko as $toko)
                        <option value="{{ $toko->id }}" @selected((string) $tokoId === (string) $toko->id)>{{ $toko->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="tanggal">Tanggal</label>
                <input class="cb-masukan" type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}">
            </div>
        </div>
        <div class="d-flex gap-2 mt-2">
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.slot-donasi.index') }}">Bersihkan</a>
        </div>
    </form>
</div>

<div class="ad-kartu">
    <div class="ad-gulir">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Tanggal</th>
                <th>Toko</th>
                <th>Jam</th>
                <th>Kuota</th>
                <th>Terisi</th>
                <th>Sisa</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $slot)
                <tr>
                    <td>{{ tanggal_id($slot->date, true) }}</td>
                    <td>{{ $slot->toko?->name ?? '-' }}</td>
                    <td>{{ jam_singkat($slot->start_time) }}–{{ jam_singkat($slot->end_time) }}</td>
                    <td>{{ $slot->quota }}</td>
                    <td>{{ $slot->booked_count }}</td>
                    <td>
                        @if($slot->sisaKuota() === 0)
                            <span class="cb-lencana cb-lencana--ingat">Penuh</span>
                        @else
                            {{ $slot->sisaKuota() }}
                        @endif
                    </td>
                    <td>
                        @if($slot->status === 'aktif')
                            <span class="cb-lencana cb-lencana--berhasil">Aktif</span>
                        @else
                            <span class="cb-lencana cb-lencana--ingat">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2 flex-wrap">
                            <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.slot-donasi.edit', $slot) }}">Ubah</a>
                            <form action="{{ route('admin.slot-donasi.destroy', $slot) }}" method="POST"
                                  data-konfirmasi="Hapus slot donasi {{ tanggal_id($slot->date) }} pukul {{ jam_singkat($slot->start_time) }}?">
                                @csrf
                                @method('DELETE')
                                <button class="cb-tombol cb-tombol--kecil cb-tombol--polos" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Belum ada slot donasi pada penyaringan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
