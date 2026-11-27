@extends('layouts.admin')

@section('judul', 'Keluhan')

@section('isi')

<div class="ad-kartu">
    <div>
        <h2 style="font-size: var(--cb-h4)" class="mb-1">Keluhan Pelanggan</h2>
        <p class="mb-0" style="color: var(--cb-tinta-lembut); font-size: var(--cb-kecil)">
            {{ $daftar->total() }} keluhan tercatat pada penyaringan ini.
        </p>
    </div>

    <form class="cb-borang mt-3" action="{{ route('admin.keluhan.index') }}" method="GET">
        <div class="d-flex gap-2 flex-wrap align-items-end">
            <div class="cb-isian mb-0">
                <label class="cb-label" for="status">Status</label>
                <select class="cb-masukan" id="status" name="status">
                    <option value="">Semua status</option>
                    @foreach($daftarStatus as $pilihan)
                        <option value="{{ $pilihan }}" @selected($status === $pilihan)>{{ ucfirst($pilihan) }}</option>
                    @endforeach
                </select>
            </div>
            <button class="cb-tombol cb-tombol--kecil" type="submit">Saring</button>
            <a class="cb-tombol cb-tombol--kecil cb-tombol--polos" href="{{ route('admin.keluhan.index') }}">Bersihkan</a>
        </div>
    </form>
</div>

<div class="ad-kartu">
    <div class="ad-gulir">
        <table class="ad-tabel">
            <thead>
            <tr>
                <th>Perihal</th>
                <th>Pelanggan</th>
                <th>Pesanan</th>
                <th>Masuk</th>
                <th>Dijawab</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daftar as $keluhan)
                <tr>
                    <td>{{ $keluhan->subject }}</td>
                    <td>
                        {{ $keluhan->pengguna?->name ?? '-' }}<br>
                        <span style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">{{ $keluhan->pengguna?->email ?? '-' }}</span>
                    </td>
                    <td>
                        @if($keluhan->pesanan)
                            <a href="{{ route('admin.pesanan.show', $keluhan->pesanan) }}">{{ $keluhan->pesanan->order_code }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ tanggal_id($keluhan->created_at) }}</td>
                    <td>{{ $keluhan->responded_at ? tanggal_id($keluhan->responded_at) : 'Belum' }}</td>
                    <td>
                        <span class="cb-lencana {{ $keluhan->status === 'selesai' ? 'cb-lencana--berhasil' : 'cb-lencana--ingat' }}">
                            {{ ucfirst($keluhan->status) }}
                        </span>
                    </td>
                    <td>
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('admin.keluhan.show', $keluhan) }}">Baca dan Jawab</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Belum ada keluhan pada penyaringan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $daftar->links() }}</div>
</div>

@endsection
