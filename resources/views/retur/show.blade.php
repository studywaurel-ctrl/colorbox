@extends('layouts.app')

@section('judul', 'Retur '.$retur->return_code)

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
            <li><a href="{{ route('retur.index') }}">Retur dan Penukaran</a></li>
            <li>{{ $retur->return_code }}</li>
        </ol>
        <div class="d-flex justify-content-between gap-3 flex-wrap align-items-center">
            <h1>{{ $retur->return_code }}</h1>
            <span class="cb-lencana {{ $retur->status === 'selesai' ? 'cb-lencana--berhasil' : 'cb-lencana--aksen' }}">
                {{ ucfirst(str_replace('_', ' ', $retur->status)) }}
            </span>
        </div>
        <p style="color: var(--cb-tinta-lembut)">
            Diajukan pada {{ tanggal_id($retur->created_at, true) }} pukul {{ $retur->created_at->format('H:i') }}
        </p>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">

            <section class="mb-5">
                <h2 style="font-size: var(--cb-h3)">Barang yang Diretur</h2>
                @foreach($retur->item as $baris)
                    <div class="cb-barang">
                        <div class="cb-barang__gambar">
                            <span class="cb-produk__kosong">{{ $baris->product_name }}</span>
                        </div>
                        <div>
                            <p class="cb-barang__nama mb-1">{{ $baris->product_name }}</p>
                            <p class="cb-barang__ket mb-0">
                                {{ $baris->note ?: 'Tanpa keterangan varian' }} · x{{ $baris->quantity }}
                            </p>
                        </div>
                        <div></div>
                    </div>
                @endforeach
            </section>

            <section class="mb-5">
                <h2 style="font-size: var(--cb-h3)">Riwayat Status</h2>
                <ol class="cb-langkah">
                    @foreach($riwayat as $catatan)
                        <li class="tercapai">
                            <span class="cb-langkah__judul">{{ $catatan['status'] }}</span>
                            <span class="d-block" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                {{ $catatan['keterangan'] }}
                            </span>
                            <span class="cb-langkah__waktu d-block">
                                {{ tanggal_id($catatan['waktu'], true) }}
                                @if($catatan['waktu']) pukul {{ $catatan['waktu']->format('H:i') }} @endif
                            </span>
                        </li>
                    @endforeach
                </ol>
            </section>

            @if($retur->photo)
                <section class="mb-5">
                    <h2 style="font-size: var(--cb-h3)">Foto Barang</h2>
                    <div class="cb-bingkai" style="max-width: 320px">
                        <img src="{{ gambar_produk($retur->photo) }}" alt="Foto barang yang diretur" style="width: 100%">
                    </div>
                </section>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="cb-panel cb-menempel">
                <h2 style="font-size: var(--cb-h4)">Keterangan Pengajuan</h2>
                <dl class="row g-2 mb-0" style="font-size: var(--cb-kecil)">
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Asal pembelian</dt>
                    <dd class="col-7 mb-0">
                        @if($retur->pesanan)
                            <a href="{{ route('pesanan.show', $retur->pesanan) }}">{{ $retur->pesanan->order_code }}</a>
                        @elseif($retur->purchase_code)
                            Pembelian di toko {{ $retur->purchase_code }}
                        @else
                            -
                        @endif
                    </dd>
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Penyelesaian</dt>
                    <dd class="col-7 mb-0">{{ $retur->type === 'refund' ? 'Pengembalian dana' : 'Penukaran barang' }}</dd>
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Cara pengembalian</dt>
                    <dd class="col-7 mb-0">{{ $retur->method === 'toko' ? 'Diantar ke toko' : 'Dikirim jasa kirim' }}</dd>
                    @if($retur->toko)
                        <dt class="col-5" style="color: var(--cb-tinta-samar)">Toko tujuan</dt>
                        <dd class="col-7 mb-0">{{ $retur->toko->name }}, {{ $retur->toko->city }}</dd>
                    @endif
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Alasan</dt>
                    <dd class="col-7 mb-0">{{ $retur->reason }}</dd>
                    @if($retur->admin_note)
                        <dt class="col-5" style="color: var(--cb-tinta-samar)">Catatan tim Colorbox</dt>
                        <dd class="col-7 mb-0">{{ $retur->admin_note }}</dd>
                    @endif
                </dl>

                @if($retur->status === 'menunggu_validasi')
                    <form class="mt-4" action="{{ route('retur.batal', $retur) }}" method="POST"
                          data-konfirmasi="Batalkan pengajuan retur ini?">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--penuh">
                            Batalkan pengajuan
                        </button>
                    </form>
                @endif

                <a class="cb-tombol cb-tombol--garis cb-tombol--penuh mt-2" href="{{ route('retur.index') }}">
                    Kembali ke daftar retur
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
