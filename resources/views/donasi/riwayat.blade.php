@extends('layouts.app')

@section('judul', 'Riwayat Donasi')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
            <li>Riwayat Donasi</li>
        </ol>
        <h1>Riwayat Donasi</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Seluruh jadwal donasi pakaian yang pernah Anda buat, beserta poin yang sudah diterima.
            Setiap potong pakaian yang diterima staf bernilai {{ $poinPerPotong }} poin.
        </p>
    </div>

    <div class="row g-5">
        <div class="col-lg-3">
            @include('akun.menu')
        </div>

        <div class="col-lg-9">
            @if($daftar->count() > 0)
                <div class="cb-gulir-datar">
                    <table class="cb-tabel">
                        <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Toko</th>
                            <th scope="col">Jadwal</th>
                            <th scope="col">Perkiraan</th>
                            <th scope="col">Diterima</th>
                            <th scope="col">Poin</th>
                            <th scope="col">Status</th>
                            <th scope="col">Rincian</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($daftar as $donasi)
                            <tr>
                                <td><strong>{{ $donasi->donation_code }}</strong></td>
                                <td>{{ $donasi->toko ? $donasi->toko->name : '-' }}</td>
                                <td>
                                    @if($donasi->slot)
                                        {{ tanggal_id($donasi->slot->date) }}<br>
                                        <span style="font-size: var(--cb-mini); color: var(--cb-tinta-lembut)">
                                            {{ jam_singkat($donasi->slot->start_time) }} - {{ jam_singkat($donasi->slot->end_time) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $donasi->estimated_items }} potong</td>
                                <td>{{ $donasi->received_items !== null ? $donasi->received_items.' potong' : '-' }}</td>
                                <td>{{ $donasi->points_awarded }}</td>
                                <td>
                                    <span class="cb-lencana
                                        @if($donasi->status === 'disetujui') cb-lencana--berhasil
                                        @elseif($donasi->status === 'dijadwalkan' || $donasi->status === 'check_in') cb-lencana--ingat
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $donasi->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a class="cb-tombol cb-tombol--garis cb-tombol--kecil"
                                       href="{{ route('donasi.show', $donasi) }}">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="cb-kosong">
                    <h2>Belum ada donasi</h2>
                    <p>Anda belum pernah menjadwalkan donasi pakaian. Serahkan pakaian layak pakai Anda dan tukarkan poinnya menjadi potongan belanja.</p>
                    <a class="cb-tombol cb-tombol--utama" href="{{ route('donasi.index') }}">Jadwalkan donasi</a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
