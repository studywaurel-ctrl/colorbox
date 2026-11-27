@extends('layouts.app')

@section('judul', 'Akun Saya')

@section('isi')
    <div class="cb-wadah cb-bagian">
        <div class="cb-judul-halaman">
            <ol class="cb-remah">
                <li><a href="{{ route('beranda') }}">Beranda</a></li>
                <li>Akun Saya</li>
            </ol>
            <h1>Halo, {{ $pengguna->name }}</h1>
            <p style="color: var(--cb-tinta-lembut)">{{ $pengguna->email }}</p>
        </div>

        <div class="row g-5">
            <div class="col-lg-3">
                @include('akun.menu')
            </div>

            <div class="col-lg-9">
                {{-- Ringkasan angka --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="cb-panel h-100">
                            <p class="mb-1" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">Level member</p>
                            <p class="mb-0" style="font-size: 1.4rem; font-weight: 700">{{ $pengguna->member_level }}</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="cb-panel h-100">
                            <p class="mb-1" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">Saldo poin</p>
                            <p class="mb-1" style="font-size: 1.4rem; font-weight: 700">{{ $pengguna->points }}</p>
                            <p class="mb-0" style="font-size: var(--cb-mini); color: var(--cb-tinta-lembut)">
                                Setara {{ rupiah($pengguna->points * (int) setting('point_value', 100)) }}
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="cb-panel h-100">
                            <p class="mb-1" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">Retur berjalan</p>
                            <p class="mb-1" style="font-size: 1.4rem; font-weight: 700">{{ $jumlahReturBerjalan }}</p>
                            <p class="mb-0" style="font-size: var(--cb-mini)">
                                <a href="{{ route('retur.index') }}">Lihat pengajuan retur</a>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Pesanan terbaru --}}
                <section class="mb-5">
                    <div class="cb-kepala-bagian">
                        <h2>Pesanan Terbaru</h2>
                        <a href="{{ route('pesanan.index') }}">Lihat semua pesanan</a>
                    </div>

                    @if($pesananTerbaru->isEmpty())
                        <p class="cb-catatan">
                            Belum ada pesanan. <a href="{{ route('produk.index') }}">Mulai belanja sekarang</a>.
                        </p>
                    @else
                        <table class="cb-tabel">
                            <thead>
                            <tr>
                                <th>Kode pesanan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pesananTerbaru as $pesanan)
                                <tr>
                                    <td>{{ $pesanan->order_code }}</td>
                                    <td>{{ tanggal_id($pesanan->created_at, true) }}</td>
                                    <td><span class="cb-lencana">{{ $pesanan->labelStatus() }}</span></td>
                                    <td>{{ rupiah($pesanan->total) }}</td>
                                    <td>
                                        <a class="cb-tombol cb-tombol--kecil cb-tombol--garis"
                                           href="{{ route('pesanan.show', $pesanan->order_code) }}">Rincian</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </section>

                {{-- Jadwal terdekat --}}
                <section class="mb-5">
                    <div class="cb-kepala-bagian">
                        <h2>Jadwal Terdekat</h2>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="cb-panel h-100">
                                <h3 style="font-size: var(--cb-kecil); font-weight: 700">Donasi Pakaian</h3>
                                @if($donasiTerdekat)
                                    <p class="mb-1">{{ tanggal_id($donasiTerdekat->slot->date, true) }},
                                        pukul {{ jam_singkat($donasiTerdekat->slot->start_time) }}
                                        &ndash; {{ jam_singkat($donasiTerdekat->slot->end_time) }}</p>
                                    <p class="mb-1" style="color: var(--cb-tinta-lembut)">
                                        {{ $donasiTerdekat->toko ? $donasiTerdekat->toko->name : 'Toko belum dipilih' }}
                                    </p>
                                    <p class="mb-2"><span class="cb-lencana">{{ $donasiTerdekat->donation_code }}</span></p>
                                    <a class="cb-tombol cb-tombol--kecil cb-tombol--garis"
                                       href="{{ route('donasi.show', $donasiTerdekat->id) }}">Lihat jadwal donasi</a>
                                @else
                                    <p class="mb-2" style="color: var(--cb-tinta-lembut)">Belum ada jadwal donasi yang akan datang.</p>
                                    <a class="cb-tombol cb-tombol--kecil cb-tombol--garis"
                                       href="{{ route('donasi.index') }}">Jadwalkan donasi</a>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="cb-panel h-100">
                                <h3 style="font-size: var(--cb-kecil); font-weight: 700">Tap Try and Collect</h3>
                                @if($ttcTerdekat)
                                    <p class="mb-1">{{ tanggal_id($ttcTerdekat->slot->date, true) }},
                                        pukul {{ jam_singkat($ttcTerdekat->slot->start_time) }}
                                        &ndash; {{ jam_singkat($ttcTerdekat->slot->end_time) }}</p>
                                    <p class="mb-1" style="color: var(--cb-tinta-lembut)">
                                        {{ $ttcTerdekat->toko ? $ttcTerdekat->toko->name : 'Toko belum dipilih' }}
                                    </p>
                                    <p class="mb-2"><span class="cb-lencana">{{ $ttcTerdekat->booking_code }}</span></p>
                                    <a class="cb-tombol cb-tombol--kecil cb-tombol--garis"
                                       href="{{ route('ttc.show', $ttcTerdekat->id) }}">Lihat jadwal kunjungan</a>
                                @else
                                    <p class="mb-2" style="color: var(--cb-tinta-lembut)">Belum ada jadwal kunjungan yang akan datang.</p>
                                    <a class="cb-tombol cb-tombol--kecil cb-tombol--garis"
                                       href="{{ route('ttc.index') }}">Pesan Tap Try and Collect</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Tautan ke seluruh halaman akun --}}
                <section>
                    <div class="cb-kepala-bagian">
                        <h2>Halaman Akun Lainnya</h2>
                    </div>

                    <div class="cb-papan">
                        <div class="cb-papan__butir">
                            <h3>Profil dan Kata Sandi</h3>
                            <p>Perbarui nama, nomor telepon, tanggal lahir, foto, dan kata sandi.</p>
                            <a href="{{ route('profil.edit') }}">Buka halaman profil</a>
                        </div>
                        <div class="cb-papan__butir">
                            <h3>Buku Alamat</h3>
                            <p>Simpan beberapa alamat pengiriman dan tentukan alamat utama.</p>
                            <a href="{{ route('alamat.index') }}">Buka buku alamat</a>
                        </div>
                        <div class="cb-papan__butir">
                            <h3>Pesanan Saya</h3>
                            <p>Riwayat pesanan, status pengiriman, dan pembelian ulang.</p>
                            <a href="{{ route('pesanan.index') }}">Buka daftar pesanan</a>
                        </div>
                        <div class="cb-papan__butir">
                            <h3>Retur dan Penukaran</h3>
                            <p>Ajukan retur pesanan daring maupun pembelian di toko.</p>
                            <a href="{{ route('retur.index') }}">Buka halaman retur</a>
                        </div>
                        <div class="cb-papan__butir">
                            <h3>Poin Saya</h3>
                            <p>Saldo poin donasi beserta riwayat masuk dan keluarnya.</p>
                            <a href="{{ route('poin.index') }}">Buka halaman poin</a>
                        </div>
                        <div class="cb-papan__butir">
                            <h3>Notifikasi</h3>
                            <p>
                                Kabar terbaru tentang pesanan dan layanan Anda.
                                @if($notifikasiBelumDibaca > 0)
                                    <span class="cb-lencana cb-lencana--aksen">{{ $notifikasiBelumDibaca }} belum dibaca</span>
                                @endif
                            </p>
                            <a href="{{ route('notifikasi.index') }}">Buka notifikasi</a>
                        </div>
                        <div class="cb-papan__butir">
                            <h3>Keluhan</h3>
                            <p>Kirim keluhan dan lihat jawaban dari tim Colorbox.</p>
                            <a href="{{ route('keluhan.index') }}">Buka halaman keluhan</a>
                        </div>
                        <div class="cb-papan__butir">
                            <h3>Riwayat Donasi</h3>
                            <p>Seluruh jadwal donasi pakaian dan poin yang diperoleh.</p>
                            <a href="{{ route('donasi.riwayat') }}">Buka riwayat donasi</a>
                        </div>
                        <div class="cb-papan__butir">
                            <h3>Riwayat Tap Try and Collect</h3>
                            <p>Daftar kunjungan mencoba dan mengambil pesanan di toko.</p>
                            <a href="{{ route('ttc.riwayat') }}">Buka riwayat kunjungan</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
