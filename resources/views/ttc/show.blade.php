@extends('layouts.app')

@section('judul', 'Booking '.$booking->booking_code)

@push('gaya')
<style>
    .ttc-qr { display: flex; justify-content: center; min-height: 200px; }
    .ttc-qr svg, .ttc-qr img { width: 200px; height: 200px; }
</style>
@endpush

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
            <li><a href="{{ route('ttc.riwayat') }}">Tap Try and Collect</a></li>
            <li>{{ $booking->booking_code }}</li>
        </ol>
        <div class="d-flex justify-content-between gap-3 flex-wrap align-items-center">
            <h1>{{ $booking->booking_code }}</h1>
            <span class="cb-lencana
                @if($booking->status === 'selesai') cb-lencana--berhasil
                @elseif($booking->status === 'dijadwalkan' || $booking->status === 'siap') cb-lencana--ingat
                @endif">
                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
            </span>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">

            <section class="cb-panel">
                <h2 style="font-size: var(--cb-h4)">Rincian jadwal</h2>
                <ul class="cb-rincian-biaya">
                    <li><span>Toko</span><strong>{{ $booking->toko ? $booking->toko->name : '-' }}</strong></li>
                    <li><span>Alamat</span><strong>{{ $booking->toko ? $booking->toko->address.', '.$booking->toko->city : '-' }}</strong></li>
                    <li><span>Tanggal</span><strong>{{ $booking->slot ? tanggal_id($booking->slot->date, true) : '-' }}</strong></li>
                    <li><span>Jam</span><strong>
                        @if($booking->slot)
                            {{ jam_singkat($booking->slot->start_time) }} - {{ jam_singkat($booking->slot->end_time) }}
                        @else
                            -
                        @endif
                    </strong></li>
                    @if($booking->purchase_code)
                        <li><span>Kode pembelian</span><strong>{{ $booking->purchase_code }}</strong></li>
                        <li class="cb-jumlah"><span>Total dibayar</span><strong>{{ rupiah($booking->total_paid) }}</strong></li>
                    @endif
                    @if($booking->verified_by)
                        <li><span>Diperiksa staf</span><strong>{{ $booking->verified_by }}</strong></li>
                    @endif
                </ul>
            </section>

            <section class="cb-panel mt-4">
                <h2 style="font-size: var(--cb-h4)">Produk yang akan dicoba</h2>
                @forelse($booking->item as $item)
                    <div class="cb-barang">
                        <div class="cb-barang__gambar">
                            <img src="{{ gambar_produk($item->varian && $item->varian->produk && $item->varian->produk->gambarUtama() ? $item->varian->produk->gambarUtama()->path : null) }}"
                                 alt="{{ $item->varian && $item->varian->produk ? $item->varian->produk->name : 'Produk' }}">
                        </div>
                        <div>
                            <div class="cb-barang__nama">
                                {{ $item->varian && $item->varian->produk ? $item->varian->produk->name : 'Produk sudah tidak tersedia' }}
                            </div>
                            <div class="cb-barang__ket">
                                {{ $item->varian ? $item->varian->label() : '-' }} &middot; {{ $item->quantity }} potong
                            </div>
                            @if($item->purchased)
                                <span class="cb-lencana cb-lencana--berhasil">Jadi dibeli</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                        Tidak ada produk pada jadwal ini.
                    </p>
                @endforelse
            </section>

            @if($booking->status === 'dijadwalkan')
                <form class="mt-4" action="{{ route('ttc.batal', $booking) }}" method="POST"
                      data-konfirmasi="Batalkan jadwal Tap Try and Collect ini?">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="cb-tombol cb-tombol--garis cb-tombol--kecil">
                        <i class="bi bi-x-circle"></i> Batalkan jadwal
                    </button>
                </form>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="cb-panel">
                <h2 style="font-size: var(--cb-h4)">Tunjukkan QR ini kepada staf toko</h2>
                <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                    Staf toko memindai QR berikut untuk membuka halaman verifikasi.
                </p>

                {{-- Tautan verifikasi staf dibaca dari atribut data-kode, lalu digambar menjadi QR --}}
                <div class="ttc-qr" id="gambar-qr" data-kode="{{ $alamatStaf }}"></div>

                <p class="mt-3 mb-0" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar); word-break: break-all">
                    {{ $alamatStaf }}
                </p>
            </div>

            <div class="cb-panel mt-4">
                <h2 style="font-size: var(--cb-h4)">Yang perlu diingat</h2>
                <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                    Datanglah pada jam yang Anda pilih. Produk hanya dibayar bila Anda jadi membelinya,
                    dan kode pembelian yang diberikan staf toko dapat dipakai bila nanti ingin mengajukan retur.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('skrip')
<script src="{{ asset('vendor/qrcode/qrcode.js') }}"></script>
<script>
// Menggambar QR dari tautan verifikasi staf yang disimpan pada atribut data-kode.
document.addEventListener('DOMContentLoaded', function () {
    var wadah = document.getElementById('gambar-qr');
    if (!wadah) { return; }

    var kode = wadah.dataset.kode;
    if (!kode) { return; }

    var qr = qrcode(0, 'M');
    qr.addData(kode);
    qr.make();
    wadah.innerHTML = qr.createSvgTag(5, 2);
});
</script>
@endpush
