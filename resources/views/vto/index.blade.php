@extends('layouts.app')

@section('judul', 'Virtual Try On')

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li>Virtual Try On</li>
        </ol>
        <h1>Virtual Try On</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Pilih produk dan warnanya, nyalakan kamera, lalu sesuaikan posisi tubuh dengan bingkai panduan.
            Foto tangkapan Anda akan disandingkan dengan foto produk sebagai pratinjau.
        </p>
    </div>

    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-papan">
            <div class="cb-papan__butir">
                <h3>1. Pilih produk dan warna</h3>
                <p>Tentukan produk yang ingin dilihat beserta warnanya.</p>
            </div>
            <div class="cb-papan__butir">
                <h3>2. Aktifkan kamera</h3>
                <p>Peramban akan meminta izin kamera terlebih dahulu.</p>
            </div>
            <div class="cb-papan__butir">
                <h3>3. Sesuaikan posisi</h3>
                <p>Berdiri menghadap kamera dan masukkan tubuh ke dalam bingkai panduan.</p>
            </div>
            <div class="cb-papan__butir">
                <h3>4. Lihat pratinjau</h3>
                <p>Hasil tangkapan ditampilkan berdampingan dengan foto produk.</p>
            </div>
        </div>
    </section>

    @if($produk->count() < 1)
        <div class="cb-kosong">
            <h2>Belum ada produk</h2>
            <p>Produk untuk Virtual Try On belum tersedia. Silakan periksa kembali nanti.</p>
            <a class="cb-tombol cb-tombol--utama" href="{{ route('produk.index') }}">Lihat katalog</a>
        </div>
    @else
        <div class="row g-5">
            <div class="col-lg-7">
                <form action="{{ route('vto.store') }}" method="POST" class="cb-borang cb-panel">
                    @csrf

                    <div class="cb-isian">
                        <label class="cb-label" for="vto-produk">Produk yang ingin dicoba</label>
                        <select class="cb-masukan" id="vto-produk" name="product_id">
                            @foreach($produk as $satu)
                                <option value="{{ $satu->id }}" @if($terpilih && $terpilih->id === $satu->id) selected @endif>
                                    {{ $satu->name }} — {{ rupiah($satu->hargaJual()) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="cb-isian">
                        <label class="cb-label" for="vto-warna">Warna</label>
                        {{-- Setiap pilihan warna ditandai dengan data-produk supaya bisa disaring JavaScript --}}
                        <select class="cb-masukan" id="vto-warna" name="color">
                            @foreach($produk as $satu)
                                @foreach($satu->varian->unique('color') as $varian)
                                    <option value="{{ $varian->color }}" data-produk="{{ $satu->id }}"
                                            @if(! $terpilih || $terpilih->id !== $satu->id) hidden @endif>
                                        {{ $varian->color }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        <span class="cb-isian__bantu">Daftar warna mengikuti produk yang dipilih.</span>
                    </div>

                    <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--penuh mt-3">
                        <i class="bi bi-camera"></i> Aktifkan Kamera
                    </button>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="cb-panel">
                    <h2 style="font-size: var(--cb-h4)">Sejujurnya, bagaimana ini bekerja</h2>
                    <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                        Halaman ini <strong>tidak memakai kecerdasan buatan</strong>. Bagian pencocokan pakaian pada
                        tubuh masih berupa <strong>pratinjau lokal</strong>: foto tangkapan kamera Anda ditampilkan
                        berdampingan dengan foto produk, lengkap dengan ukuran yang Anda pilih.
                    </p>
                    <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                        Pratinjau ini sudah siap disambungkan ke layanan virtual try-on. Mode layanan dibaca dari
                        <code>config('colorbox.try_on.mode')</code> dengan dua pilihan:
                        <strong>preview</strong> (pratinjau lokal seperti sekarang) dan <strong>api</strong>
                        (hasilnya dibuat layanan luar). Mode yang sedang dipakai: <strong>{{ $mode }}</strong>.
                    </p>
                </div>

                <div class="cb-panel mt-4">
                    <h2 style="font-size: var(--cb-h4)">Tentang kamera Anda</h2>
                    <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                        Kamera baru menyala setelah Anda memberi izin pada peramban. Bila kamera ditolak atau tidak
                        tersedia, Anda tetap bisa mengunggah foto dari galeri pada halaman berikutnya.
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@push('skrip')
<script>
// Menyaring daftar warna mengikuti produk yang sedang dipilih.
document.addEventListener('DOMContentLoaded', function () {
    var pilihProduk = document.getElementById('vto-produk');
    var pilihWarna = document.getElementById('vto-warna');
    if (!pilihProduk || !pilihWarna) { return; }

    function saringWarna() {
        var idProduk = pilihProduk.value;
        var pertama = null;

        Array.prototype.forEach.call(pilihWarna.options, function (pilihan) {
            var cocok = (pilihan.dataset.produk === idProduk);
            pilihan.hidden = !cocok;
            pilihan.disabled = !cocok;
            if (cocok && !pertama) { pertama = pilihan; }
        });

        if (pertama) { pilihWarna.value = pertama.value; }
    }

    pilihProduk.addEventListener('change', saringWarna);
    saringWarna();
});
</script>
@endpush
