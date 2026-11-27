@extends('layouts.app')

@section('judul', 'Poin Saya')

@section('isi')
    <div class="cb-wadah cb-bagian">
        <div class="cb-judul-halaman">
            <ol class="cb-remah">
                <li><a href="{{ route('beranda') }}">Beranda</a></li>
                <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
                <li>Poin Saya</li>
            </ol>
            <h1>Poin Saya</h1>
        </div>

        <div class="row g-5">
            <div class="col-lg-3">
                @include('akun.menu')
            </div>

            <div class="col-lg-9">
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="cb-panel h-100">
                            <p class="mb-1" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">Saldo poin</p>
                            <p class="mb-0" style="font-size: 1.62rem; font-weight: 700">{{ $pengguna->points }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="cb-panel h-100">
                            <p class="mb-1" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">Nilai tukar</p>
                            <p class="mb-0" style="font-size: 1.62rem; font-weight: 700">
                                {{ rupiah($pengguna->points * $nilaiPoin) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Penjelasan singkat cara memperoleh dan memakai poin --}}
                <section class="cb-panel cb-panel--aksen mb-4">
                    <h2 style="font-size: var(--cb-kecil); font-weight: 700">Cara Memperoleh dan Memakai Poin</h2>
                    <ul class="mb-0 ps-3">
                        <li>
                            Poin diperoleh dari donasi pakaian layak pakai di toko Colorbox.
                            Setiap satu potong pakaian yang diterima petugas bernilai
                            {{ $poinPerPotong }} poin.
                        </li>
                        <li>
                            Poin baru masuk setelah petugas toko memeriksa dan menyetujui donasi Anda,
                            bukan saat jadwal donasi dibuat.
                        </li>
                        <li>
                            Satu poin bernilai {{ rupiah($nilaiPoin) }} ketika ditukar menjadi potongan
                            belanja pada halaman checkout.
                        </li>
                        <li>
                            Poin yang dipakai paling banyak {{ $batasPersen }} persen dari subtotal belanja,
                            jadi tidak seluruh tagihan dapat dibayar memakai poin.
                        </li>
                    </ul>

                    <p class="mt-3 mb-0">
                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis" href="{{ route('donasi.index') }}">
                            Jadwalkan donasi pakaian
                        </a>
                    </p>
                </section>

                <div class="cb-kepala-bagian">
                    <h2>Riwayat Poin</h2>
                </div>

                @if($riwayatPoin->isEmpty())
                    <p class="cb-catatan">Belum ada riwayat poin. Poin pertama Anda muncul setelah donasi disetujui.</p>
                @else
                    <table class="cb-tabel">
                        <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Sumber</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($riwayatPoin as $baris)
                            <tr>
                                <td>{{ tanggal_id($baris->created_at, true) }}</td>
                                <td>{{ ucfirst($baris->source) }}</td>
                                <td>{{ $baris->note ?: '-' }}</td>
                                <td>
                                    @if($baris->type === 'masuk')
                                        <span class="cb-lencana cb-lencana--berhasil">+{{ $baris->amount }}</span>
                                    @else
                                        <span class="cb-lencana cb-lencana--ingat">&minus;{{ $baris->amount }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $riwayatPoin->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
