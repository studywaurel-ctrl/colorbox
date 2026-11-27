@extends('layouts.app')

@section('judul', 'Ajukan Retur')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('retur.index') }}">Retur dan Penukaran</a></li>
            <li>Pengajuan baru</li>
        </ol>
        <h1>Ajukan Retur atau Penukaran</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            @if($pesanan)
                Dari pesanan {{ $pesanan->order_code }} yang dibuat {{ tanggal_id($pesanan->created_at) }}.
            @elseif($booking)
                Dari pembelian {{ $booking->purchase_code }}
                @if($booking->toko) di {{ $booking->toko->name }} @endif
                pada {{ tanggal_id($booking->completed_at ?: $booking->updated_at) }}.
            @endif
            Pengajuan ditunggu paling lambat {{ $hariRetur }} hari sejak barang diterima.
        </p>
    </div>

    @if($barang->count() === 0)
        <div class="cb-kosong">
            <h2>Tidak ada barang yang dapat diretur</h2>
            <p>Barang pada pembelian ini tidak tercatat sebagai barang yang dibeli.</p>
            <a class="cb-tombol cb-tombol--utama" href="{{ route('retur.index') }}">Kembali ke halaman retur</a>
        </div>
    @else
        <form action="{{ route('retur.store', $kode) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-5">
                <div class="col-lg-7">

                    <section class="mb-5">
                        <h2 style="font-size: var(--cb-h3)">1. Pilih barang yang diretur</h2>
                        @foreach($barang as $baris)
                            <div class="cb-barang">
                                <div class="cb-barang__gambar">
                                    <img src="{{ gambar_produk($baris['gambar']) }}" alt="{{ $baris['nama'] }}">
                                </div>
                                <div>
                                    <label class="d-flex gap-2 align-items-start">
                                        <input type="checkbox" name="item[]" value="{{ $baris['kunci'] }}"
                                               style="margin-top: 5px">
                                        <span>
                                            <span class="cb-barang__nama d-block">{{ $baris['nama'] }}</span>
                                            <span class="cb-barang__ket d-block">
                                                {{ $baris['ket'] }} · dibeli {{ $baris['maks'] }} pcs
                                            </span>
                                        </span>
                                    </label>

                                    <div class="cb-isian mt-2" style="max-width: 200px">
                                        <label class="cb-label" for="jml-{{ $baris['kunci'] }}">Jumlah diretur</label>
                                        <input class="cb-masukan" type="number" id="jml-{{ $baris['kunci'] }}"
                                               name="jumlah[{{ $baris['kunci'] }}]" value="1"
                                               min="1" max="{{ $baris['maks'] }}">
                                    </div>
                                </div>
                                <div></div>
                            </div>
                        @endforeach
                    </section>

                    <section class="mb-5">
                        <h2 style="font-size: var(--cb-h3)">2. Ceritakan masalahnya</h2>
                        <div class="cb-borang mt-3">
                            <div class="cb-isian">
                                <label class="cb-label" for="reason">Alasan pengajuan</label>
                                <input class="cb-masukan" type="text" id="reason" name="reason" maxlength="191" required
                                       value="{{ old('reason') }}"
                                       placeholder="Contoh: ada cacat jahitan pada bagian lengan">
                                <p class="cb-isian__bantu">
                                    Tuliskan bagian mana yang bermasalah dan kapan Anda menemukannya.
                                </p>
                            </div>

                            <div class="cb-isian">
                                <label class="cb-label" for="type">Penyelesaian yang diharapkan</label>
                                <select class="cb-masukan" id="type" name="type" required>
                                    <option value="tukar" {{ old('type') === 'tukar' ? 'selected' : '' }}>
                                        Tukar dengan barang lain
                                    </option>
                                    <option value="refund" {{ old('type') === 'refund' ? 'selected' : '' }}>
                                        Kembalikan dananya
                                    </option>
                                </select>
                            </div>

                            <div class="cb-isian">
                                <label class="cb-label" for="photo">Foto barang (opsional)</label>
                                <input class="cb-masukan" type="file" id="photo" name="photo" accept="image/*">
                                <p class="cb-isian__bantu">
                                    Format JPG atau PNG, ukuran paling besar 2 MB. Foto membantu tim Colorbox
                                    memeriksa keluhan Anda lebih cepat.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="mb-5">
                        <h2 style="font-size: var(--cb-h3)">3. Cara pengembalian</h2>
                        <div class="d-grid gap-3 mt-3">
                            <label class="cb-kartu-pilih">
                                <input type="radio" name="method" value="toko" id="cara-toko"
                                       {{ old('method', 'toko') === 'toko' ? 'checked' : '' }}>
                                <span class="cb-kartu-pilih__isi">
                                    <span class="cb-kartu-pilih__tanda"></span>
                                    <span>
                                        <span class="cb-kartu-pilih__judul">Saya antar sendiri ke toko</span>
                                        <span class="cb-kartu-pilih__ket d-block">Pilih toko tujuan pada panel di samping.</span>
                                    </span>
                                </span>
                            </label>
                            <label class="cb-kartu-pilih">
                                <input type="radio" name="method" value="online" id="cara-online"
                                       {{ old('method') === 'online' ? 'checked' : '' }}>
                                <span class="cb-kartu-pilih__isi">
                                    <span class="cb-kartu-pilih__tanda"></span>
                                    <span>
                                        <span class="cb-kartu-pilih__judul">Saya kirim lewat jasa kirim</span>
                                        <span class="cb-kartu-pilih__ket d-block">
                                            Alamat tujuan pengembalian dikirimkan setelah pengajuan disetujui.
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </section>
                </div>

                <div class="col-lg-5">
                    <div class="cb-panel cb-menempel">
                        <h2 style="font-size: var(--cb-h4)">Toko Tujuan</h2>
                        <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                            Diperlukan bila Anda mengantar sendiri barangnya ke toko.
                        </p>

                        <div class="cb-isian" id="kotak-toko">
                            <label class="cb-label" for="store_id">Toko</label>
                            <select class="cb-masukan" id="store_id" name="store_id">
                                <option value="">— Pilih toko —</option>
                                @foreach($daftarToko as $toko)
                                    <option value="{{ $toko->id }}"
                                        {{ (int) old('store_id', $booking?->store_id) === $toko->id ? 'selected' : '' }}>
                                        {{ $toko->name }} — {{ $toko->city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <p class="cb-catatan cb-catatan--ingat">
                            Barang yang diretur harus lengkap dengan label dan struk pembeliannya.
                        </p>

                        <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--penuh mt-2">
                            Kirim pengajuan
                        </button>
                        <a class="cb-tombol cb-tombol--polos cb-tombol--penuh mt-2" href="{{ route('retur.index') }}">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    @endif

</div>
@endsection

@push('skrip')
<script>
    // Pilihan toko hanya ditampilkan bila barang diantar sendiri ke toko
    document.addEventListener('DOMContentLoaded', function () {
        var caraToko = document.getElementById('cara-toko');
        var caraOnline = document.getElementById('cara-online');
        var kotakToko = document.getElementById('kotak-toko');

        function perbarui() {
            if (!kotakToko || !caraToko) { return; }
            kotakToko.hidden = !caraToko.checked;
        }

        if (caraToko) { caraToko.addEventListener('change', perbarui); }
        if (caraOnline) { caraOnline.addEventListener('change', perbarui); }
        perbarui();
    });
</script>
@endpush
