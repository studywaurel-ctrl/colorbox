@extends('layouts.app')

@section('judul', 'Pembayaran Pesanan '.$pesanan->order_code)

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-tahap">
            <li class="lewat">1. Rincian pesanan</li>
            <li class="aktif">2. Pembayaran</li>
            <li>3. Selesai</li>
        </ol>
        <h1>Selesaikan Pembayaran</h1>
        <p style="color: var(--cb-tinta-lembut)">Nomor pesanan {{ $pesanan->order_code }}</p>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">

            <div class="cb-panel"
                 id="kotak-bayar"
                 data-token="{{ $pembayaran->snap_token }}"
                 data-status-url="{{ route('pembayaran.status', $pembayaran) }}"
                 data-pesanan-url="{{ route('pesanan.show', $pesanan) }}"
                 data-selesai-url="{{ route('pembayaran.selesai', $pembayaran) }}"
                 data-batas="{{ $pembayaran->expired_at?->toIso8601String() }}">

                <p class="mb-1" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">Jumlah yang harus dibayar</p>
                <p style="font-size: 1.42rem; font-weight: 700">{{ rupiah($pembayaran->gross_amount) }}</p>

                <p class="mb-1" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">Status pembayaran</p>
                <p style="font-weight: 600" id="tulisan-status">
                    {{ ucfirst(str_replace('_', ' ', $pembayaran->payment_status)) }}
                </p>

                @if($pembayaran->expired_at)
                    <p class="cb-catatan cb-catatan--ingat">
                        Pembayaran ditunggu sampai {{ tanggal_id($pembayaran->expired_at, true) }}
                        pukul {{ $pembayaran->expired_at->format('H:i') }}.
                        Sisa waktu <strong id="hitung-mundur">menghitung…</strong>.
                        Lewat dari itu pesanan dibatalkan sistem dan stoknya dikembalikan.
                    </p>
                @endif

                @if($pembayaran->payment_status === 'lunas')
                    <p class="cb-catatan cb-catatan--berhasil">
                        Pembayaran pesanan ini sudah kami terima. Terima kasih.
                    </p>
                    <a class="cb-tombol cb-tombol--utama" href="{{ route('pesanan.show', $pesanan) }}">
                        Lihat rincian pesanan
                    </a>
                @elseif(! $midtransAktif)
                    <p class="cb-catatan cb-catatan--ingat">
                        Kunci Midtrans belum diisi pada berkas <code>.env</code>
                        (<code>MIDTRANS_SERVER_KEY</code> dan <code>MIDTRANS_CLIENT_KEY</code>),
                        sehingga jendela pembayaran belum dapat dibuka. Pesanan Anda tetap tersimpan
                        dan dapat dibayar setelah kuncinya diisi.
                    </p>
                @elseif(! $pembayaran->snap_token)
                    <p class="cb-catatan">
                        Kami belum berhasil menyiapkan jendela pembayaran untuk pesanan ini.
                        Silakan muat ulang halaman ini beberapa saat lagi.
                    </p>
                @else
                    <button type="button" class="cb-tombol cb-tombol--utama cb-tombol--penuh mt-2" id="tombol-bayar">
                        <i class="bi bi-credit-card"></i> Bayar Sekarang
                    </button>
                    <p class="mt-3 mb-0" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                        Pilihan QRIS, Virtual Account bank, dan dompet digital muncul pada jendela pembayaran Midtrans.
                        Status pembayaran diperbarui sendiri oleh halaman ini setelah pembayaran Anda diterima.
                    </p>
                    @if($pembayaran->snap_url)
                        <p class="mt-2 mb-0" style="font-size: var(--cb-mini)">
                            Jendela tidak terbuka?
                            <a href="{{ $pembayaran->snap_url }}" target="_blank" rel="noopener">Buka halaman pembayaran Midtrans</a>.
                        </p>
                    @endif
                @endif
            </div>

            <section class="mt-4">
                <h2 style="font-size: var(--cb-h3)">Barang yang Dipesan</h2>
                @foreach($pesanan->item as $baris)
                    <div class="cb-barang">
                        <div class="cb-barang__gambar">
                            <img src="{{ gambar_produk($baris->image_path) }}" alt="{{ $baris->product_name }}">
                        </div>
                        <div>
                            <p class="cb-barang__nama mb-1">{{ $baris->product_name }}</p>
                            <p class="cb-barang__ket mb-0">
                                {{ $baris->color }} · {{ $baris->size }} · x{{ $baris->quantity }}
                            </p>
                        </div>
                        <div class="text-end" style="font-weight: 600">{{ rupiah($baris->subtotal) }}</div>
                    </div>
                @endforeach
            </section>
        </div>

        <div class="col-lg-5">
            <div class="cb-panel cb-menempel">
                <h2 style="font-size: var(--cb-h4)">Ringkasan Pembayaran</h2>
                <ul class="cb-rincian-biaya">
                    <li><span>Subtotal</span><span>{{ rupiah($pesanan->subtotal) }}</span></li>
                    <li><span>Ongkos kirim</span>
                        <span>{{ $pesanan->shipping_cost > 0 ? rupiah($pesanan->shipping_cost) : 'Gratis' }}</span></li>
                    @if($pesanan->discount > 0)
                        <li class="cb-hemat"><span>Potongan voucher</span>
                            <span>&minus; {{ rupiah($pesanan->discount) }}</span></li>
                    @endif
                    @if($pesanan->points_used > 0)
                        <li class="cb-hemat"><span>Potongan poin ({{ $pesanan->points_used }} poin)</span>
                            <span>&minus; {{ rupiah($pesanan->points_used * (int) setting('point_value', 100)) }}</span></li>
                    @endif
                    <li class="cb-jumlah"><span>Total pembayaran</span><span>{{ rupiah($pesanan->total) }}</span></li>
                </ul>

                <hr>
                <dl class="row g-2 mb-0" style="font-size: var(--cb-kecil)">
                    <dt class="col-5" style="color: var(--cb-tinta-samar)">Cara menerima</dt>
                    <dd class="col-7 mb-0">{{ $pesanan->method === 'ambil_toko' ? 'Diambil di toko' : 'Dikirim kurir' }}</dd>
                    @if($pesanan->alamat)
                        <dt class="col-5" style="color: var(--cb-tinta-samar)">Dikirim ke</dt>
                        <dd class="col-7 mb-0">{{ $pesanan->alamat->recipient }} — {{ $pesanan->alamat->detail }}, {{ $pesanan->alamat->city }}</dd>
                    @endif
                    @if($pesanan->toko)
                        <dt class="col-5" style="color: var(--cb-tinta-samar)">Toko pengambilan</dt>
                        <dd class="col-7 mb-0">{{ $pesanan->toko->name }}, {{ $pesanan->toko->city }}</dd>
                    @endif
                </dl>

                <a class="cb-tombol cb-tombol--garis cb-tombol--penuh mt-4" href="{{ route('pesanan.show', $pesanan) }}">
                    Lihat rincian pesanan
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@push('skrip')
<script src="{{ config('midtrans.snap_js') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    // Seluruh keterangan pembayaran dibaca dari atribut data-... pada elemen,
    // sehingga tidak ada data yang ditulis langsung di dalam JavaScript.
    document.addEventListener('DOMContentLoaded', function () {
        var kotak = document.getElementById('kotak-bayar');
        if (!kotak) { return; }

        var token = kotak.dataset.token;
        var alamatStatus = kotak.dataset.statusUrl;
        var alamatPesanan = kotak.dataset.pesananUrl;
        var alamatSelesai = kotak.dataset.selesaiUrl;
        var batas = kotak.dataset.batas;

        // ------------------------------------------------ tombol Bayar Sekarang
        var tombol = document.getElementById('tombol-bayar');
        if (tombol && token) {
            tombol.addEventListener('click', function () {
                if (!window.snap) {
                    window.alert('Jendela pembayaran belum siap. Silakan muat ulang halaman ini.');
                    return;
                }
                window.snap.pay(token, {
                    onSuccess: function () { window.location.href = alamatSelesai; },
                    onPending: function () { window.location.href = alamatSelesai; },
                    onError: function () { window.location.href = alamatSelesai; },
                    onClose: function () { window.location.href = alamatSelesai; }
                });
            });
        }

        // ---------------------------------------------------- hitung mundur
        var tulisanMundur = document.getElementById('hitung-mundur');
        if (tulisanMundur && batas) {
            var waktuBatas = new Date(batas).getTime();
            var hitung = function () {
                var sisa = waktuBatas - new Date().getTime();
                if (sisa <= 0) {
                    tulisanMundur.textContent = 'habis';
                    return;
                }
                var jam = Math.floor(sisa / 3600000);
                var menit = Math.floor((sisa % 3600000) / 60000);
                var detik = Math.floor((sisa % 60000) / 1000);
                tulisanMundur.textContent = jam + ' jam ' + menit + ' menit ' + detik + ' detik';
            };
            hitung();
            window.setInterval(hitung, 1000);
        }

        // --------------------------------- pemeriksaan status setiap 5 detik
        var tulisanStatus = document.getElementById('tulisan-status');
        window.setInterval(function () {
            fetch(alamatStatus, { headers: { 'Accept': 'application/json' } })
                .then(function (jawaban) { return jawaban.json(); })
                .then(function (hasil) {
                    if (!hasil || !hasil.status) { return; }
                    if (tulisanStatus) {
                        tulisanStatus.textContent = hasil.status.charAt(0).toUpperCase() + hasil.status.slice(1);
                    }
                    if (hasil.status === 'lunas') {
                        window.location.href = alamatPesanan;
                    }
                })
                .catch(function () { /* jaringan sedang terganggu, dicoba lagi nanti */ });
        }, 5000);
    });
</script>
@endpush
