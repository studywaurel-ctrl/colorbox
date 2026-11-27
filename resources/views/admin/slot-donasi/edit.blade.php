@extends('layouts.admin')

@section('judul', 'Ubah Slot Donasi')

@section('isi')

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Ubah Slot Donasi</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Tanggal, toko, dan jam slot tidak bisa diubah. Bila jadwalnya keliru, nonaktifkan
        slot ini lalu buat slot baru.
    </p>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <tbody>
            <tr><th>Toko</th><td>{{ $slot->toko?->name ?? '-' }}</td></tr>
            <tr><th>Tanggal</th><td>{{ tanggal_id($slot->date, true) }}</td></tr>
            <tr><th>Jam</th><td>{{ jam_singkat($slot->start_time) }}–{{ jam_singkat($slot->end_time) }}</td></tr>
            <tr><th>Sudah dipesan</th><td>{{ $slot->booked_count }} pendonor</td></tr>
            <tr>
                <th>Sisa kuota</th>
                <td>
                    @if($slot->sisaKuota() === 0)
                        <span class="cb-lencana cb-lencana--ingat">Penuh</span>
                    @else
                        {{ $slot->sisaKuota() }}
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.slot-donasi.update', $slot) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="cb-baris-isian cb-baris-isian--dua">
            <div class="cb-isian">
                <label class="cb-label" for="quota">Kuota</label>
                <input class="cb-masukan" type="number" id="quota" name="quota" min="0" max="200" required
                       value="{{ old('quota', $slot->quota) }}">
                <p class="cb-isian__bantu">Tidak boleh lebih kecil daripada {{ $slot->booked_count }} booking yang sudah masuk.</p>
            </div>
            <div class="cb-isian">
                <label class="cb-label" for="status">Status slot</label>
                <select class="cb-masukan" id="status" name="status" required>
                    <option value="aktif" @selected(old('status', $slot->status) === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $slot->status) === 'nonaktif')>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button class="cb-tombol cb-tombol--utama" type="submit">Simpan Perubahan</button>
            <a class="cb-tombol cb-tombol--polos" href="{{ route('admin.slot-donasi.index') }}">Batal</a>
        </div>
    </form>
</div>

@endsection
