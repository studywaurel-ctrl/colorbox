@extends('layouts.app')

@section('judul', 'Pesanan Saya')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
            <li>Pesanan Saya</li>
        </ol>
        <h1>Pesanan Saya</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Seluruh pesanan Anda tercatat di sini, mulai dari yang masih menunggu pembayaran sampai yang sudah selesai.
        </p>
    </div>

    <div class="row g-5">
        <div class="col-lg-3">
            @include('akun.menu')
        </div>

        <div class="col-lg-9">
            <div class="cb-saring mb-4">
                <a class="cb-cip {{ $status ? '' : 'aktif' }}" href="{{ route('pesanan.index') }}">Semua</a>
                @foreach($daftarStatus as $pilihan)
                    <a class="cb-cip {{ $status === $pilihan ? 'aktif' : '' }}"
                       href="{{ route('pesanan.index', ['status' => $pilihan]) }}">
                        {{ ucfirst(str_replace('_', ' ', $pilihan)) }}
                    </a>
                @endforeach
            </div>

            @forelse($daftar as $pesanan)
                <div class="cb-panel mb-3">
                    <div class="d-flex justify-content-between gap-3 flex-wrap align-items-center">
                        <div>
                            <p class="mb-1" style="font-weight: 600">
                                <a href="{{ route('pesanan.show', $pesanan) }}">{{ $pesanan->order_code }}</a>
                            </p>
                            <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                Dibuat {{ tanggal_id($pesanan->created_at, true) }} ·
                                {{ $pesanan->item->count() }} jenis barang ·
                                {{ $pesanan->method === 'ambil_toko' ? 'Diambil di toko' : 'Dikirim kurir' }}
                            </p>
                        </div>
                        <span class="cb-lencana {{ $pesanan->status === 'dibatalkan' ? 'cb-lencana--ingat' : 'cb-lencana--aksen' }}">
                            {{ $pesanan->labelStatus() }}
                        </span>
                    </div>

                    <div class="d-flex gap-2 flex-wrap mt-3" style="font-size: var(--cb-kecil)">
                        @foreach($pesanan->item->take(3) as $baris)
                            <span style="color: var(--cb-tinta-lembut)">
                                {{ $baris->product_name }} ({{ $baris->color }} / {{ $baris->size }}) &times; {{ $baris->quantity }}
                            </span>
                        @endforeach
                        @if($pesanan->item->count() > 3)
                            <span style="color: var(--cb-tinta-samar)">dan {{ $pesanan->item->count() - 3 }} barang lainnya</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between gap-3 flex-wrap align-items-center mt-3">
                        <p class="mb-0" style="font-weight: 600">Total {{ rupiah($pesanan->total) }}</p>
                        <div class="d-flex gap-2 flex-wrap">
                            @if($pesanan->status === 'menunggu_pembayaran' && $pesanan->pembayaran)
                                <a class="cb-tombol cb-tombol--utama cb-tombol--kecil"
                                   href="{{ route('pembayaran.show', $pesanan->pembayaran) }}">Bayar sekarang</a>
                            @endif
                            <a class="cb-tombol cb-tombol--garis cb-tombol--kecil"
                               href="{{ route('pesanan.show', $pesanan) }}">Lihat rincian</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="cb-kosong">
                    <h2>Belum ada pesanan</h2>
                    <p>Pesanan yang Anda buat akan muncul di halaman ini.</p>
                    <a class="cb-tombol cb-tombol--utama" href="{{ route('produk.index') }}">Mulai belanja</a>
                </div>
            @endforelse

            {{ $daftar->links() }}
        </div>
    </div>

</div>
@endsection
