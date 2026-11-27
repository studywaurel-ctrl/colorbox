@extends('layouts.app')

@section('judul', 'Donasi Pakaian')

@push('gaya')
<style>
    .donasi-jam { display: grid; gap: var(--cb-s2); grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); }
</style>
@endpush

@section('isi')
<div class="cb-wadah">

    <div class="cb-judul-halaman">
        <ol class="cb-remah">
            <li><a href="{{ route('beranda') }}">Beranda</a></li>
            <li>Donasi Pakaian</li>
        </ol>
        <h1>Donasi Pakaian</h1>
        <p style="color: var(--cb-tinta-lembut); max-width: 64ch">
            Serahkan pakaian layak pakai Anda di toko Colorbox dan tukarkan poinnya menjadi potongan belanja.
            Semua jenis pakaian diterima, baik dari Colorbox maupun merek lain.
        </p>
    </div>

    <section class="cb-bagian cb-bagian--rapat">
        <div class="cb-papan">
            <div class="cb-papan__butir">
                <h3>1. Jadwalkan kunjungan</h3>
                <p>Pilih toko penerima donasi, tanggal, dan jam penyerahan pakaian.</p>
            </div>
            <div class="cb-papan__butir">
                <h3>2. Serahkan pakaian</h3>
                <p>Datang pada jam yang dipilih, lalu tunjukkan QR jadwal donasi kepada staf toko.</p>
            </div>
            <div class="cb-papan__butir">
                <h3>3. Poin masuk</h3>
                <p>Staf toko menghitung pakaian yang diterima, lalu poinnya masuk ke akun Anda.</p>
            </div>
        </div>
    </section>

    @if($toko->count() < 1)
        <div class="cb-kosong">
            <h2>Belum ada toko penerima donasi</h2>
            <p>Program donasi pakaian belum tersedia. Silakan periksa kembali nanti.</p>
            <a class="cb-tombol cb-tombol--utama" href="{{ route('toko.index') }}">Lihat daftar toko</a>
        </div>
    @else
        <div class="row g-5">
            <div class="col-lg-7">
                <form action="{{ route('donasi.store') }}" method="POST" class="cb-borang">
                    @csrf

                    {{-- 1. Toko dan tanggal --}}
                    <section>
                        <h2 style="font-size: var(--cb-h3)">1. Toko dan tanggal penyerahan</h2>

                        <div class="cb-isian mt-3">
                            <label class="cb-label" for="donasi-toko">Toko penerima donasi</label>
                            <select class="cb-masukan" id="donasi-toko" name="toko">
                                @foreach($toko as $satu)
                                    <option value="{{ $satu->id }}">{{ $satu->name }} — {{ $satu->city }}</option>
                                @endforeach
                            </select>
                            <span class="cb-isian__bantu">Hanya toko yang menerima donasi pakaian yang ditampilkan.</span>
                        </div>

                        <div class="cb-isian">
                            <span class="cb-label">Tanggal penyerahan</span>
                            <div class="cb-pilihan__daftar" id="donasi-tanggal">
                                @foreach($tanggal as $nomor => $satu)
                                    <label class="cb-pilih">
                                        <input type="radio" name="tanggal" value="{{ $satu }}" @if($nomor === 0) checked @endif>
                                        <span>{{ tanggal_id($satu, true) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    {{-- 2. Jam penyerahan, diambil lewat fetch ke route donasi.slot --}}
                    <section class="mt-5">
                        <h2 style="font-size: var(--cb-h3)">2. Jam penyerahan</h2>
                        <p style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                            Jam donasi berbeda dengan jam Tap Try and Collect. Sisa kuota setiap jam ditulis apa adanya
                            dan jam yang penuh tidak dapat dipilih.
                        </p>

                        {{-- Alamat endpoint disimpan pada atribut data-alamat, lalu dibaca JavaScript --}}
                        <div id="donasi-daftar-jam" class="donasi-jam" data-alamat="{{ route('donasi.slot') }}" aria-live="polite">
                            <p class="cb-catatan">Sedang mengambil daftar jam...</p>
                        </div>
                    </section>

                    {{-- 3. Pakaian yang dibawa --}}
                    <section class="mt-5">
                        <h2 style="font-size: var(--cb-h3)">3. Pakaian yang dibawa</h2>

                        <div class="cb-isian mt-3">
                            <label class="cb-label" for="donasi-jumlah">Perkiraan jumlah pakaian (potong)</label>
                            <input class="cb-masukan" type="number" id="donasi-jumlah" name="estimated_items"
                                   value="{{ old('estimated_items', $minItem) }}" min="{{ $minItem }}" max="{{ $maksItem }}">
                            <span class="cb-isian__bantu">
                                Paling sedikit {{ $minItem }} potong dan paling banyak {{ $maksItem }} potong per kunjungan.
                                Jumlah akhir tetap dihitung staf toko.
                            </span>
                        </div>

                        <div class="cb-isian">
                            <label class="cb-label" for="donasi-kondisi">Catatan kondisi pakaian</label>
                            <input class="cb-masukan" type="text" id="donasi-kondisi" name="condition_note"
                                   value="{{ old('condition_note') }}" maxlength="191"
                                   placeholder="Misalnya: sudah dicuci bersih, 3 potong ada noda kecil">
                            <span class="cb-isian__bantu">
                                Pakaian sebaiknya sudah dicuci bersih. Pakaian dalam tidak diterima.
                            </span>
                        </div>
                    </section>

                    <div class="mt-5">
                        @auth
                            <button type="submit" class="cb-tombol cb-tombol--utama">Jadwalkan donasi</button>
                        @else
                            <p class="cb-catatan cb-catatan--ingat">
                                Anda perlu masuk ke akun terlebih dahulu supaya poin donasi dapat dicatat.
                            </p>
                            <a class="cb-tombol cb-tombol--utama" href="{{ route('login') }}">Masuk ke akun</a>
                        @endauth
                    </div>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="cb-panel">
                    <h2 style="font-size: var(--cb-h4)">Bagaimana poin dihitung</h2>
                    <ul class="cb-rincian-biaya">
                        <li>
                            <span>Setiap potong pakaian yang diterima staf</span>
                            <strong>{{ $poinPerPotong }} poin</strong>
                        </li>
                        <li>
                            <span>Nilai satu poin saat checkout</span>
                            <strong>{{ rupiah(setting('point_value', 100)) }}</strong>
                        </li>
                    </ul>
                    <p class="cb-catatan cb-catatan--aksen mt-3 mb-0">
                        Poin baru masuk ke akun Anda setelah pakaian diserahkan dan diperiksa staf toko.
                        Pakaian yang tidak layak tidak dihitung.
                    </p>
                </div>

                <div class="cb-panel mt-4">
                    <h2 style="font-size: var(--cb-h4)">Ke mana pakaiannya disalurkan</h2>
                    <p class="mb-0" style="font-size: var(--cb-kecil); color: var(--cb-tinta-lembut)">
                        Pakaian layak pakai disalurkan kepada penerima manfaat melalui program tanggung jawab sosial
                        perusahaan, sedangkan pakaian yang sudah tidak layak pakai diteruskan untuk didaur ulang.
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@push('skrip')
<script>
// Mengambil daftar jam donasi sesuai toko dan tanggal yang dipilih.
// Slot donasi dibaca dari endpoint donasi.slot, terpisah dari slot Tap Try and Collect.
document.addEventListener('DOMContentLoaded', function () {
    var wadah = document.getElementById('donasi-daftar-jam');
    var pilihToko = document.getElementById('donasi-toko');
    var daftarTanggal = document.getElementById('donasi-tanggal');
    if (!wadah || !pilihToko || !daftarTanggal) { return; }

    function tanggalTerpilih() {
        var pilihan = daftarTanggal.querySelector('input[name="tanggal"]:checked');
        return pilihan ? pilihan.value : '';
    }

    function tulisPesan(teks) {
        wadah.innerHTML = '';
        var p = document.createElement('p');
        p.className = 'cb-catatan';
        p.textContent = teks;
        wadah.appendChild(p);
    }

    function ambilJam() {
        var tanggal = tanggalTerpilih();
        if (!pilihToko.value || !tanggal) { return; }

        tulisPesan('Sedang mengambil daftar jam...');

        fetch(wadah.dataset.alamat + '?toko=' + encodeURIComponent(pilihToko.value) + '&tanggal=' + encodeURIComponent(tanggal), {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (jawaban) { return jawaban.json(); })
        .then(function (data) {
            if (!data.slot || data.slot.length < 1) {
                tulisPesan('Belum ada jam donasi pada tanggal tersebut. Silakan pilih tanggal lainnya.');
                return;
            }

            wadah.innerHTML = '';

            data.slot.forEach(function (slot) {
                var label = document.createElement('label');
                label.className = 'cb-kartu-pilih';

                var masukan = document.createElement('input');
                masukan.type = 'radio';
                masukan.name = 'donation_slot_id';
                masukan.value = slot.id;
                masukan.disabled = slot.penuh;

                var isi = document.createElement('span');
                isi.className = 'cb-kartu-pilih__isi';

                var tanda = document.createElement('span');
                tanda.className = 'cb-kartu-pilih__tanda';

                var teks = document.createElement('span');

                var judul = document.createElement('span');
                judul.className = 'cb-kartu-pilih__judul';
                judul.textContent = slot.jam;

                var ket = document.createElement('span');
                ket.className = 'cb-kartu-pilih__ket d-block';
                // Tulisannya jelas, tidak hanya mengandalkan warna
                ket.textContent = slot.penuh ? 'Penuh' : (slot.sisa + ' slot tersisa');

                teks.appendChild(judul);
                teks.appendChild(ket);
                isi.appendChild(tanda);
                isi.appendChild(teks);
                label.appendChild(masukan);
                label.appendChild(isi);

                if (slot.penuh) {
                    label.style.opacity = '0.55';
                    label.style.cursor = 'not-allowed';
                }

                wadah.appendChild(label);
            });
        })
        .catch(function () {
            tulisPesan('Daftar jam gagal diambil. Silakan coba lagi.');
        });
    }

    pilihToko.addEventListener('change', ambilJam);
    daftarTanggal.addEventListener('change', ambilJam);
    ambilJam();
});
</script>
@endpush
