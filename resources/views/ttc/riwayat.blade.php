@extends('layouts.app')

@section('judul', 'Riwayat Tap Try and Collect')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
            <li>Tap Try and Collect</li>
        </ol>
        <h1>Riwayat Tap Try and Collect</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Seluruh jadwal mencoba produk di toko yang pernah Anda buat.
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
                            <th scope="col">Jumlah produk</th>
                            <th scope="col">Status</th>
                            <th scope="col">Rincian</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($daftar as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->toko ? $booking->toko->name : '-' }}</td>
                                <td>
                                    @if($booking->slot)
                                        {{ tanggal_id($booking->slot->date) }}<br>
                                        <span style="font-size: var(--cb-mini); color: var(--cb-tinta-lembut)">
                                            {{ jam_singkat($booking->slot->start_time) }} - {{ jam_singkat($booking->slot->end_time) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $booking->item->count() }} produk</td>
                                <td>
                                    <span class="cb-lencana
                                        @if($booking->status === 'selesai') cb-lencana--berhasil
                                        @elseif($booking->status === 'dijadwalkan' || $booking->status === 'siap') cb-lencana--ingat
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a class="cb-tombol cb-tombol--garis cb-tombol--kecil"
                                       href="{{ route('ttc.show', $booking) }}">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="cb-kosong">
                    <h2>Belum ada jadwal</h2>
                    <p>Anda belum pernah membuat jadwal Tap Try and Collect. Pilih produk favorit Anda, lalu coba langsung di toko.</p>
                    <a class="cb-tombol cb-tombol--utama" href="{{ route('ttc.index') }}">Buat jadwal sekarang</a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
