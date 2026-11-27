@extends('layouts.app')

@section('judul', 'Status Pembayaran '.$pesanan->order_code)

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-tahap">
            <li class="lewat">1. Rincian pesanan</li>
            <li class="lewat">2. Pembayaran</li>
            <li class="aktif">3. Selesai</li>
        </ol>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">
            @if($pembayaran->payment_status === 'lunas')
                <span class="cb-lencana cb-lencana--berhasil mb-3">Pembayaran diterima</span>
                <h1>Terima kasih, pembayaran Anda sudah kami terima.</h1>
                <p style="color: var(--cb-tinta-lembut); max-width: 58ch">
                    Nomor pesanan Anda {{ $pesanan->order_code }}. Simpan nomor ini untuk melacak pesanan
                    maupun mengajukan retur bila diperlukan.
                </p>
            @elseif(in_array($pembayaran->payment_status, ['gagal', 'kedaluwarsa', 'dibatalkan']))
                <span class="cb-lencana cb-lencana--ingat mb-3">Pembayaran tidak selesai</span>
                <h1>Pembayaran belum berhasil.</h1>
                <p style="color: var(--cb-tinta-lembut); max-width: 58ch">
                    Status pembayaran pesanan {{ $pesanan->order_code }} tercatat
                    {{ str_replace('_', ' ', $pembayaran->payment_status) }}. Stok yang sempat dikunci sudah
                    dikembalikan. Anda dapat memesan ulang barangnya kapan saja.
                </p>
            @else
                <span class="cb-lencana cb-lencana--aksen mb-3">Menunggu pembayaran</span>
                <h1>Pembayaran Anda sedang kami tunggu.</h1>
                <p style="color: var(--cb-tinta-lembut); max-width: 58ch">
                    Kalau Anda sudah membayar, statusnya akan berubah sendiri setelah pemberitahuan dari Midtrans
                    kami terima. Tidak perlu melakukan konfirmasi apa pun.
                </p>
            @endif

            <div class="cb-panel mt-4">
                <h2 style="font-size: var(--cb-h4)">Keterangan pembayaran</h2>
                <dl class="row g-2 mb-0" style="font-size: var(--cb-kecil)">
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Status</dt>
                    <dd class="col-7 mb-0">{{ ucfirst(str_replace('_', ' ', $pembayaran->payment_status)) }}</dd>
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Cara pembayaran</dt>
                    <dd class="col-7 mb-0">{{ $pembayaran->payment_method ?: 'Belum dipilih' }}</dd>
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Jumlah</dt>
                    <dd class="col-7 mb-0">{{ rupiah($pembayaran->gross_amount) }}</dd>
                    @if($pembayaran->paid_at)
                        <dt class="col-5" style="color: var(--cb-tinta-samar)">Dibayar pada</dt>
                        <dd class="col-7 mb-0">
                            {{ tanggal_id($pembayaran->paid_at, true) }} pukul {{ $pembayaran->paid_at->format('H:i') }}
                        </dd>
                    @endif
                </dl>
            </div>

            <div class="d-flex gap-2 flex-wrap mt-4">
                <a class="cb-tombol cb-tombol--utama" href="{{ route('pesanan.show', $pesanan) }}">Lihat rincian pesanan</a>
                @if($pembayaran->payment_status === 'menunggu')
                    <a class="cb-tombol cb-tombol--garis" href="{{ route('pembayaran.show', $pembayaran) }}">
                        Kembali ke halaman pembayaran
                    </a>
                @endif
                <a class="cb-tombol cb-tombol--polos" href="{{ route('produk.index') }}">Lanjut berbelanja</a>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="cb-panel">
                <h2 style="font-size: var(--cb-h4)">Ringkasan</h2>
                @foreach($pesanan->item as $baris)
                    <div class="d-flex justify-content-between gap-3 py-2"
                         style="border-bottom: 1px solid var(--cb-garis); font-size: var(--cb-kecil)">
                        <div>
                            <div style="font-weight: 600">{{ $baris->product_name }}</div>
                            <div style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">
                                {{ $baris->color }} · {{ $baris->size }} · x{{ $baris->quantity }}
                            </div>
                        </div>
                        <div style="font-weight: 600">{{ rupiah($baris->subtotal) }}</div>
                    </div>
                @endforeach

                <ul class="cb-rincian-biaya">
                    <li><span>Subtotal</span><span>{{ rupiah($pesanan->subtotal) }}</span></li>
                    <li><span>Ongkos kirim</span>
                        <span>{{ $pesanan->shipping_cost > 0 ? rupiah($pesanan->shipping_cost) : 'Gratis' }}</span></li>
                    <li class="cb-jumlah"><span>Total</span><span>{{ rupiah($pesanan->total) }}</span></li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
