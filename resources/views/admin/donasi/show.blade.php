@extends('layouts.admin')

@section('judul', 'Donasi '.$donasi->donation_code)

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">Donasi {{ $donasi->donation_code }}</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                Didaftarkan {{ tanggal_id($donasi->created_at, true) }}.
            </p>
        </div>
        <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.donasi.index') }}">Kembali ke Daftar</a>
    </div>

    <div class="ad-angka mt-3">
        <div>
            <p class="ad-angka__nilai mb-0">{{ ucfirst(str_replace('_', ' ', $donasi->status)) }}</p>
            <p class="ad-angka__label mb-0">Status donasi</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $donasi->estimated_items }}</p>
            <p class="ad-angka__label mb-0">Perkiraan pakaian (potong)</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $donasi->received_items !== null ? $donasi->received_items : '-' }}</p>
            <p class="ad-angka__label mb-0">Diterima staf toko (potong)</p>
        </div>
        <div>
            <p class="ad-angka__nilai mb-0">{{ $donasi->points_awarded ?: 0 }}</p>
            <p class="ad-angka__label mb-0">Poin yang diberikan</p>
        </div>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Keterangan Donasi</h2>

    <div class="ad-gulir mt-2">
        <table class="ad-tabel">
            <tbody>
            <tr>
                <th>Pendonor</th>
                <td>
                    {{ $donasi->pengguna?->name ?? '-' }}
                    @if($donasi->pengguna)
                        <br><span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">{{ $donasi->pengguna->email }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Toko</th>
                <td>{{ $donasi->toko?->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jadwal</th>
                <td>
                    @if($donasi->slot)
                        {{ tanggal_id($donasi->slot->date, true) }},
                        pukul {{ jam_singkat($donasi->slot->start_time) }}–{{ jam_singkat($donasi->slot->end_time) }}
                    @else
                        Jadwal sudah tidak ada.
                    @endif
                </td>
            </tr>
            <tr>
                <th>Check in</th>
                <td>{{ $donasi->checked_in_at ? tanggal_id($donasi->checked_in_at, true) : 'Belum check in' }}</td>
            </tr>
            <tr>
                <th>Catatan kondisi pakaian</th>
                <td>{{ $donasi->condition_note ?: '-' }}</td>
            </tr>
            <tr>
                <th>Divalidasi oleh</th>
                <td>{{ $donasi->verified_by ?: 'Belum divalidasi' }}</td>
            </tr>
            <tr>
                <th>Divalidasi pada</th>
                <td>{{ $donasi->verified_at ? tanggal_id($donasi->verified_at, true) : '-' }}</td>
            </tr>
            <tr>
                <th>Alasan penolakan</th>
                <td>{{ $donasi->reject_reason ?: '-' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Validasi Donasi</h2>
    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Setiap satu potong pakaian yang diterima bernilai {{ $poinPerPotong }} poin.
        Poin baru masuk ke akun pendonor setelah donasi disetujui.
    </p>

    @if(in_array($donasi->status, ['disetujui', 'ditolak', 'dibatalkan']))
        <p class="cb-catatan cb-catatan--ingat mb-0">
            Donasi ini sudah divalidasi sebelumnya, jadi tidak bisa divalidasi ulang.
        </p>
    @else
        <form class="cb-borang mt-2" action="{{ route('admin.donasi.validasi', $donasi) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="cb-isian">
                <label class="cb-label" for="keputusan">Keputusan</label>
                <select class="cb-masukan" id="keputusan" name="keputusan" required style="max-width: 260px">
                    <option value="setujui" @selected(old('keputusan') === 'setujui')>Setujui donasi</option>
                    <option value="tolak" @selected(old('keputusan') === 'tolak')>Tolak donasi</option>
                </select>
            </div>

            <div class="cb-baris-isian cb-baris-isian--dua">
                <div class="cb-isian">
                    <label class="cb-label" for="received_items">Jumlah pakaian yang diterima</label>
                    <input class="cb-masukan" type="number" id="received_items" name="received_items" min="0" max="500"
                           value="{{ old('received_items', $donasi->received_items ?? $donasi->estimated_items) }}">
                    <p class="cb-isian__bantu">Wajib diisi paling sedikit 1 bila donasi disetujui.</p>
                </div>
                <div class="cb-isian">
                    <label class="cb-label" for="condition_note">Catatan kondisi pakaian</label>
                    <input class="cb-masukan" type="text" id="condition_note" name="condition_note" maxlength="191"
                           value="{{ old('condition_note', $donasi->condition_note) }}" placeholder="Misalnya: semua layak pakai">
                </div>
            </div>

            <div class="cb-isian">
                <label class="cb-label" for="reject_reason">Alasan penolakan</label>
                <input class="cb-masukan" type="text" id="reject_reason" name="reject_reason" maxlength="191"
                       value="{{ old('reject_reason') }}" placeholder="Diisi hanya bila donasi ditolak">
            </div>

            <button class="cb-tombol cb-tombol--utama cb-tombol--kecil" type="submit">Simpan Validasi</button>
        </form>
    @endif
</div>

@endsection
