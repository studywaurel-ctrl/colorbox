@extends('layouts.app')

@section('judul', 'Retur dan Penukaran')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
            <li>Retur dan Penukaran</li>
        </ol>
        <h1>Retur dan Penukaran</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Pengajuan ditunggu paling lambat {{ $hariRetur }} hari sejak pesanan diterima atau sejak tanggal
            pembelian di toko. Pastikan label masih menempel dan barang belum dicuci atau dipakai.
        </p>
    </div>

    <div class="row g-5">
        <div class="col-lg-3">
            @include('akun.menu')
        </div>

        <div class="col-lg-9">

            <section class="cb-bagian cb-bagian--rapat">
                <h2 style="font-size: var(--cb-h3)">Ajukan retur baru</h2>
                <div class="row g-4 mt-1">
                    <div class="col-md-6">
                        <div class="cb-panel h-100">
                            <h3 style="font-size: var(--cb-h4)">Pesanan daring</h3>
                            <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                Pilih pesanan yang barangnya sudah Anda terima dan masih dalam batas
                                {{ $hariRetur }} hari.
                            </p>

                            @forelse($pesananBisaRetur as $pesanan)
                                <div class="d-flex justify-content-between gap-3 py-2 align-items-center"
                                     style="border-bottom: 1px solid var(--cb-garis); font-size: var(--cb-kecil)">
                                    <span>
                                        {{ $pesanan->order_code }}
                                        <span class="d-block" style="color: var(--cb-tinta-samar)">
                                            {{ $pesanan->item->count() }} jenis barang · {{ rupiah($pesanan->total) }}
                                        </span>
                                    </span>
                                    <a class="cb-tombol cb-tombol--garis cb-tombol--kecil"
                                       href="{{ route('retur.create', $pesanan->order_code) }}">Pilih</a>
                                </div>
                            @empty
                                <p class="cb-catatan mb-0">
                                    Belum ada pesanan daring yang dapat diretur saat ini.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="cb-panel h-100">
                            <h3 style="font-size: var(--cb-h4)">Pembelian di toko</h3>
                            <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                Masukkan kode pembelian yang tertera pada struk, termasuk struk pengambilan
                                Tap Try and Collect. Masalah yang baru ketahuan di rumah tetap dapat diurus dari rumah.
                            </p>

                            <form action="{{ route('retur.cari-struk') }}" method="POST" class="cb-borang">
                                @csrf
                                <div class="cb-isian">
                                    <label class="cb-label" for="purchase_code">Kode pembelian</label>
                                    <input class="cb-masukan" type="text" id="purchase_code" name="purchase_code"
                                           maxlength="40" value="{{ old('purchase_code') }}"
                                           placeholder="Misalnya TTC-BX-240915">
                                </div>
                                <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--kecil">
                                    Cari struk
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section class="cb-bagian cb-bagian--rapat">
                <h2 style="font-size: var(--cb-h3)">Pengajuan Anda</h2>

                @forelse($daftar as $retur)
                    <div class="cb-panel mb-3">
                        <div class="d-flex justify-content-between gap-3 flex-wrap align-items-center">
                            <div>
                                <p class="mb-1" style="font-weight: 600">
                                    <a href="{{ route('retur.show', $retur) }}">{{ $retur->return_code }}</a>
                                </p>
                                <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                    Diajukan {{ tanggal_id($retur->created_at, true) }} ·
                                    {{ $retur->type === 'refund' ? 'Pengembalian dana' : 'Penukaran barang' }} ·
                                    {{ $retur->method === 'toko' ? 'Diantar ke toko' : 'Dikirim jasa kirim' }}
                                </p>
                            </div>
                            <span class="cb-lencana {{ $retur->status === 'selesai' ? 'cb-lencana--berhasil' : 'cb-lencana--aksen' }}">
                                {{ ucfirst(str_replace('_', ' ', $retur->status)) }}
                            </span>
                        </div>

                        <p class="mt-3 mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                            @if($retur->pesanan)
                                Dari pesanan {{ $retur->pesanan->order_code }}
                            @elseif($retur->purchase_code)
                                Dari pembelian di toko {{ $retur->purchase_code }}
                            @endif
                            · {{ $retur->item->count() }} barang
                        </p>
                    </div>
                @empty
                    <div class="cb-kosong">
                        <h2>Belum ada pengajuan retur</h2>
                        <p>Pengajuan retur atau penukaran yang Anda kirim akan muncul di halaman ini.</p>
                    </div>
                @endforelse
            </section>
        </div>
    </div>

</div>
@endsection
