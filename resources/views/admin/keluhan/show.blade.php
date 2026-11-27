@extends('layouts.admin')

@section('judul', 'Keluhan Pelanggan')

@section('isi')

<div class="ad-kartu">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 style="font-size: var(--cb-h4)" class="mb-1">{{ $keluhan->subject }}</h2>
            <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
                Dikirim {{ tanggal_id($keluhan->created_at, true) }}.
            </p>
        </div>
        <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.keluhan.index') }}">Kembali ke Daftar</a>
    </div>

    <div class="ad-gulir mt-3">
        <table class="ad-tabel">
            <tbody>
            <tr>
                <th>Pelanggan</th>
                <td>
                    {{ $keluhan->pengguna?->name ?? '-' }}
                    @if($keluhan->pengguna)
                        <br><span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">
                            {{ $keluhan->pengguna->email }} · {{ $keluhan->pengguna->phone ?: 'tanpa nomor telepon' }}
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Pesanan terkait</th>
                <td>
                    @if($keluhan->pesanan)
                        <a href="{{ route('admin.pesanan.show', $keluhan->pesanan) }}">{{ $keluhan->pesanan->order_code }}</a>
                    @else
                        Tidak dikaitkan dengan pesanan.
                    @endif
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($keluhan->status) }}</td>
            </tr>
            <tr>
                <th>Dijawab pada</th>
                <td>{{ $keluhan->responded_at ? tanggal_id($keluhan->responded_at, true) : 'Belum dijawab' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Isi Keluhan</h2>
    <p class="mb-0" style="white-space: pre-line">{{ $keluhan->message }}</p>
</div>

<div class="ad-kartu">
    <h2 style="font-size: var(--cb-h4)">Jawaban untuk Pelanggan</h2>

    @if($keluhan->response)
        <p class="cb-catatan cb-catatan--berhasil" style="white-space: pre-line">{{ $keluhan->response }}</p>
    @else
        <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">Keluhan ini belum dijawab.</p>
    @endif

    <p style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
        Setelah jawaban disimpan, keluhan langsung ditandai selesai dan pelanggan mendapat pemberitahuan.
    </p>

    <form class="cb-borang mt-2" action="{{ route('admin.keluhan.jawab', $keluhan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="cb-isian">
            <label class="cb-label" for="response">Tulis jawaban</label>
            <textarea class="cb-masukan" id="response" name="response" rows="5" maxlength="2000" required>{{ old('response', $keluhan->response) }}</textarea>
        </div>

        <button class="cb-tombol cb-tombol--utama cb-tombol--kecil" type="submit">Kirim Jawaban</button>
    </form>
</div>

@endsection
