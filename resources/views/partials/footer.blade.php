<footer class="cb-kaki">
    <div class="cb-wadah">
        <div class="cb-kaki__kisi">
            <div>
                <h4>Belanja</h4>
                <ul>
                    <li><a href="{{ route('produk.baru') }}">New Arrival</a></li>
                    <li><a href="{{ route('produk.index') }}">Semua Produk</a></li>
                    <li><a href="{{ route('produk.index', ['koleksi' => 'Colorbox x Liunic']) }}">Koleksi Kolaborasi</a></li>
                    <li><a href="{{ route('bantuan.show', 'panduan-ukuran') }}">Panduan Ukuran</a></li>
                </ul>
            </div>
            <div>
                <h4>Layanan Colorbox</h4>
                <ul>
                    <li><a href="{{ route('vto.index') }}">Virtual Try On</a></li>
                    <li><a href="{{ route('ttc.index') }}">Tap Try and Collect</a></li>
                    <li><a href="{{ route('donasi.index') }}">Donasi Poin</a></li>
                    <li><a href="{{ route('toko.index') }}">Daftar Toko</a></li>
                </ul>
            </div>
            <div>
                <h4>Bantuan</h4>
                <ul>
                    <li><a href="{{ route('lacak.index') }}">Lacak Pesanan</a></li>
                    <li><a href="{{ route('bantuan.show', 'pengiriman') }}">Pengiriman</a></li>
                    <li><a href="{{ route('retur.index') }}">Retur dan Penukaran</a></li>
                    <li><a href="{{ route('bantuan.index') }}">Pusat Bantuan</a></li>
                </ul>
            </div>
            <div>
                <h4>Tentang</h4>
                <ul>
                    <li><a href="{{ route('donasi.index') }}">Program Donasi</a></li>
                    <li><a href="{{ route('akun.index') }}">Akun dan Keanggotaan</a></li>
                    <li><a href="{{ route('bantuan.show', 'tentang-colorbox') }}">Tentang Colorbox</a></li>
                </ul>
            </div>
        </div>
        <div class="cb-kaki__bawah">
            <span>&copy; {{ date('Y') }} Colorbox. Seluruh hak cipta dilindungi.</span>
            <form action="{{ route('tema.ubah') }}" method="POST" class="cb-pilih-kecil">
                @csrf
                <button type="submit" name="tema" value="light" aria-pressed="{{ session('tema', 'light') === 'light' ? 'true' : 'false' }}" aria-label="Tema terang">
                    <i class="bi bi-sun"></i>
                </button>
                <button type="submit" name="tema" value="dark" aria-pressed="{{ session('tema') === 'dark' ? 'true' : 'false' }}" aria-label="Tema gelap">
                    <i class="bi bi-moon"></i>
                </button>
            </form>
        </div>
    </div>
</footer>
