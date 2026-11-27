@extends('layouts.app')

@section('judul', 'Rincian Pesanan')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-tahap">
            <li class="aktif">1. Rincian pesanan</li>
            <li>2. Pembayaran</li>
            <li>3. Selesai</li>
        </ol>
        <h1>Rincian Pesanan</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Periksa kembali barang, alamat, dan biaya sebelum pesanan dibuat.
        </p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row g-5">
            <div class="col-lg-7">

                <section class="mb-5">
                    <h2 style="font-size: var(--cb-h3)">Cara menerima barang</h2>
                    <div class="d-grid gap-3 mt-3">
                        <label class="cb-kartu-pilih">
                            <input type="radio" name="method" value="kirim" id="metode-kirim"
                                   {{ old('method', 'kirim') === 'kirim' ? 'checked' : '' }}>
                            <span class="cb-kartu-pilih__isi">
                                <span class="cb-kartu-pilih__tanda"></span>
                                <span>
                                    <span class="cb-kartu-pilih__judul">Dikirim ke alamat saya</span>
                                    <span class="cb-kartu-pilih__ket d-block">
                                        Diantar jasa kirim pilihan Anda. Gratis ongkos kirim untuk belanja mulai
                                        {{ rupiah($batasGratisOngkir) }}.
                                    </span>
                                </span>
                            </span>
                        </label>
                        <label class="cb-kartu-pilih">
                            <input type="radio" name="method" value="ambil_toko" id="metode-toko"
                                   {{ old('method') === 'ambil_toko' ? 'checked' : '' }}>
                            <span class="cb-kartu-pilih__isi">
                                <span class="cb-kartu-pilih__tanda"></span>
                                <span>
                                    <span class="cb-kartu-pilih__judul">Diambil sendiri di toko</span>
                                    <span class="cb-kartu-pilih__ket d-block">
                                        Tanpa ongkos kirim. Barang disiapkan staf toko dan diambil di kasir.
                                    </span>
                                </span>
                            </span>
                        </label>
                    </div>
                </section>

                <section class="mb-5" id="bagian-kirim">
                    <h2 style="font-size: var(--cb-h3)">Alamat pengiriman</h2>

                    @if($alamat->count() > 0)
                        <div class="d-grid gap-3 mt-3">
                            @foreach($alamat as $baris)
                                <label class="cb-kartu-pilih">
                                    <input type="radio" name="address_id" value="{{ $baris->id }}"
                                           {{ (int) old('address_id', $alamat->first()->id) === $baris->id ? 'checked' : '' }}>
                                    <span class="cb-kartu-pilih__isi">
                                        <span class="cb-kartu-pilih__tanda"></span>
                                        <span>
                                            <span class="cb-kartu-pilih__judul">
                                                {{ $baris->label }} — {{ $baris->recipient }}
                                                @if($baris->is_primary)
                                                    <span class="cb-lencana cb-lencana--aksen">Utama</span>
                                                @endif
                                            </span>
                                            <span class="cb-kartu-pilih__ket d-block">
                                                {{ $baris->detail }}, {{ $baris->district }}, {{ $baris->city }},
                                                {{ $baris->province }} {{ $baris->postal_code }} · {{ $baris->phone }}
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="cb-catatan cb-catatan--ingat mt-3">
                            Anda belum menyimpan alamat pengiriman. Tambahkan alamat dulu supaya pesanan dapat dikirim.
                        </p>
                    @endif

                    <a class="cb-tombol cb-tombol--polos mt-3 d-inline-block" href="{{ route('alamat.create') }}">
                        <i class="bi bi-plus-lg"></i> Tambah alamat baru
                    </a>

                    <h2 class="mt-5" style="font-size: var(--cb-h3)">Jasa kirim</h2>
                    <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                        Colorbox tidak mengoperasikan kurir sendiri, sehingga pengiriman dipercayakan kepada mitra berikut.
                    </p>
                    <div class="d-grid gap-3">
                        @forelse($jasaKirim as $tarif)
                            <label class="cb-kartu-pilih">
                                <input type="radio" name="shipping_rate_id" value="{{ $tarif->id }}"
                                       {{ (int) old('shipping_rate_id', $jasaKirim->first()->id) === $tarif->id ? 'checked' : '' }}>
                                <span class="cb-kartu-pilih__isi">
                                    <span class="cb-kartu-pilih__tanda"></span>
                                    <span class="flex-grow-1">
                                        <span class="cb-kartu-pilih__judul">{{ $tarif->courier }} {{ $tarif->service }}</span>
                                        <span class="cb-kartu-pilih__ket d-block">
                                            Perkiraan tiba {{ $tarif->etd_min }} – {{ $tarif->etd_max }} hari kerja
                                        </span>
                                    </span>
                                    <span style="font-weight: 600; font-size: var(--cb-kecil)">
                                        {{ $gratisOngkir ? 'Gratis' : rupiah($tarif->cost) }}
                                    </span>
                                </span>
                            </label>
                        @empty
                            <p class="cb-catatan">Belum ada jasa kirim yang aktif.</p>
                        @endforelse
                    </div>
                </section>

                <section class="mb-5" id="bagian-toko" hidden>
                    <h2 style="font-size: var(--cb-h3)">Toko pengambilan</h2>
                    <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                        Daftar di bawah hanya berisi toko yang melayani pengambilan barang.
                    </p>
                    <div class="d-grid gap-3">
                        @forelse($daftarToko as $toko)
                            <label class="cb-kartu-pilih">
                                <input type="radio" name="store_id" value="{{ $toko->id }}"
                                       {{ (int) old('store_id') === $toko->id ? 'checked' : '' }}>
                                <span class="cb-kartu-pilih__isi">
                                    <span class="cb-kartu-pilih__tanda"></span>
                                    <span>
                                        <span class="cb-kartu-pilih__judul">{{ $toko->name }}</span>
                                        <span class="cb-kartu-pilih__ket d-block">
                                            {{ $toko->address }}, {{ $toko->city }} ·
                                            Buka {{ jam_singkat($toko->open_time) }} – {{ jam_singkat($toko->close_time) }}
                                        </span>
                                    </span>
                                </span>
                            </label>
                        @empty
                            <p class="cb-catatan">Belum ada toko yang melayani pengambilan barang.</p>
                        @endforelse
                    </div>
                </section>

                <section class="mb-5">
                    <h2 style="font-size: var(--cb-h3)">Poin Colorbox</h2>
                    <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                        Anda memiliki {{ number_format($poinDimiliki, 0, ',', '.') }} poin. Satu poin setara
                        {{ rupiah($nilaiPoin) }} dan poin dapat menutup paling banyak {{ $persenPoin }} persen dari subtotal,
                        yaitu {{ number_format($poinMaks, 0, ',', '.') }} poin untuk belanja ini.
                    </p>
                    <div class="cb-isian" style="max-width: 240px">
                        <label class="cb-label" for="poin">Poin yang dipakai</label>
                        <input class="cb-masukan" type="number" id="poin" name="poin"
                               value="{{ old('poin', 0) }}" min="0" max="{{ $poinMaks }}">
                        <p class="cb-isian__bantu">Kosongkan atau isi 0 bila tidak ingin memakai poin.</p>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 style="font-size: var(--cb-h3)">Cara pembayaran</h2>
                    <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                        Pembayaran diproses Midtrans. Cara pembayarannya dipilih pada jendela pembayaran
                        setelah pesanan dibuat, dengan pilihan berikut.
                    </p>
                    <div class="cb-kisi cb-kisi--tiga">
                        <div class="cb-panel">
                            <h3 style="font-size: var(--cb-h4)"><i class="bi bi-qr-code"></i> QRIS</h3>
                            <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                Dipindai memakai aplikasi bank atau dompet digital apa pun.
                            </p>
                        </div>
                        <div class="cb-panel">
                            <h3 style="font-size: var(--cb-h4)"><i class="bi bi-bank"></i> Virtual Account</h3>
                            <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                Nomor Virtual Account bank BCA, BNI, BRI, Mandiri, dan Permata.
                            </p>
                        </div>
                        <div class="cb-panel">
                            <h3 style="font-size: var(--cb-h4)"><i class="bi bi-wallet2"></i> Dompet digital</h3>
                            <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                                GoPay, ShopeePay, dan dompet digital lain yang didukung Midtrans.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 style="font-size: var(--cb-h3)">Catatan untuk toko</h2>
                    <div class="cb-isian">
                        <label class="cb-label" for="note">Catatan (opsional)</label>
                        <input class="cb-masukan" type="text" id="note" name="note" maxlength="191"
                               value="{{ old('note') }}" placeholder="Contoh: tolong dibungkus kado">
                    </div>
                </section>
            </div>

            <div class="col-lg-5">
                <div class="cb-panel cb-menempel">
                    <h2 style="font-size: var(--cb-h4)">Ringkasan Belanja</h2>

                    @foreach($item as $baris)
                        @php
                            $varian = $baris->varian;
                            $produk = $varian ? $varian->produk : null;
                            $harga = $produk ? $produk->hargaJual() : 0;
                        @endphp
                        <div class="d-flex gap-3 py-2" style="border-bottom: 1px solid var(--cb-garis)">
                            <div style="width: 56px; flex: none">
                                <img src="{{ gambar_produk($produk?->gambarUtama()?->path) }}"
                                     alt="{{ $produk?->name }}" style="width: 100%; border-radius: 6px">
                            </div>
                            <div class="flex-grow-1" style="font-size: var(--cb-kecil)">
                                <div style="font-weight: 600">{{ $produk?->name ?: 'Produk tidak tersedia' }}</div>
                                <div style="color: var(--cb-tinta-samar); font-size: var(--cb-mini)">
                                    {{ $varian ? $varian->label() : '-' }} · x{{ $baris->quantity }}
                                </div>
                            </div>
                            <div style="font-size: var(--cb-kecil); font-weight: 600">
                                {{ rupiah($harga * $baris->quantity) }}
                            </div>
                        </div>
                    @endforeach

                    <ul class="cb-rincian-biaya">
                        <li><span>Subtotal</span><span>{{ rupiah($subtotal) }}</span></li>
                        <li><span>Ongkos kirim</span>
                            <span>{{ $gratisOngkir ? 'Gratis' : 'Dihitung sesuai jasa kirim' }}</span></li>
                        @if($voucher)
                            <li class="cb-hemat"><span>Voucher {{ $voucher->code }}</span>
                                <span>&minus; {{ rupiah($diskon) }}</span></li>
                        @endif
                        <li class="cb-jumlah"><span>Perkiraan total</span>
                            <span>{{ rupiah(max(0, $subtotal - $diskon)) }}</span></li>
                    </ul>
                    <p style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                        Ongkos kirim dan potongan poin dihitung ulang saat pesanan dibuat.
                    </p>

                    <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--penuh mt-3">
                        Buat pesanan
                    </button>
                    <p class="mt-3 mb-0" style="font-size: var(--cb-mini); color: var(--cb-tinta-samar)">
                        Dengan membuat pesanan, Anda menyetujui ketentuan pengiriman dan retur Colorbox.
                    </p>
                </div>
            </div>
        </div>
    </form>

    {{-- Formulir voucher ditulis terpisah supaya tidak ikut terkirim saat pesanan dibuat --}}
    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-panel" style="max-width: 520px">
            <h2 style="font-size: var(--cb-h4)">Voucher belanja</h2>
            @if($voucher)
                <p style="font-size: var(--cb-kecil)">
                    Voucher <strong>{{ $voucher->code }}</strong> — {{ $voucher->name }} dipakai,
                    potongan {{ rupiah($diskon) }}.
                </p>
                <form action="{{ route('checkout.voucher.hapus') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cb-tombol cb-tombol--polos cb-tombol--kecil">Lepas voucher</button>
                </form>
            @else
                <form action="{{ route('checkout.voucher') }}" method="POST" class="cb-borang">
                    @csrf
                    <div class="cb-isian">
                        <label class="cb-label" for="kode">Kode voucher</label>
                        <input class="cb-masukan" type="text" id="kode" name="kode" maxlength="30"
                               placeholder="Contoh: COLORBOX10">
                    </div>
                    <button type="submit" class="cb-tombol cb-tombol--garis cb-tombol--kecil">Pakai voucher</button>
                </form>
            @endif
        </div>
    </section>

</div>
@endsection

@push('skrip')
<script>
    // Menampilkan bagian alamat atau bagian toko sesuai cara menerima barang
    document.addEventListener('DOMContentLoaded', function () {
        var pilihanKirim = document.getElementById('metode-kirim');
        var pilihanToko = document.getElementById('metode-toko');
        var bagianKirim = document.getElementById('bagian-kirim');
        var bagianToko = document.getElementById('bagian-toko');

        function perbarui() {
            if (!bagianKirim || !bagianToko || !pilihanToko) { return; }
            var ambilDiToko = pilihanToko.checked;
            bagianKirim.hidden = ambilDiToko;
            bagianToko.hidden = !ambilDiToko;
        }

        if (pilihanKirim) { pilihanKirim.addEventListener('change', perbarui); }
        if (pilihanToko) { pilihanToko.addEventListener('change', perbarui); }
        perbarui();
    });
</script>
@endpush
