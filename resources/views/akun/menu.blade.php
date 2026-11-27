{{-- Menu samping halaman akun. Dipakai ulang oleh seluruh halaman akun. --}}
<nav class="cb-panel" aria-label="Menu akun">
    <p class="cb-kepala-bagian mb-2" style="font-size: var(--cb-kecil); font-weight: 700">Menu Akun</p>

    <ul style="list-style: none; padding: 0; margin: 0; display: grid; gap: var(--cb-s1)">
        <li>
            <a class="d-block py-2" href="{{ route('akun.index') }}"
               @if(request()->routeIs('akun.index')) aria-current="page" @endif>
                <i class="bi bi-grid"></i> Ringkasan
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('profil.edit') }}"
               @if(request()->routeIs('profil.edit')) aria-current="page" @endif>
                <i class="bi bi-person"></i> Profil dan Kata Sandi
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('alamat.index') }}"
               @if(request()->routeIs('alamat.*')) aria-current="page" @endif>
                <i class="bi bi-geo-alt"></i> Buku Alamat
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('pesanan.index') }}"
               @if(request()->routeIs('pesanan.*')) aria-current="page" @endif>
                <i class="bi bi-bag-check"></i> Pesanan Saya
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('retur.index') }}"
               @if(request()->routeIs('retur.*')) aria-current="page" @endif>
                <i class="bi bi-arrow-repeat"></i> Retur dan Penukaran
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('poin.index') }}"
               @if(request()->routeIs('poin.*')) aria-current="page" @endif>
                <i class="bi bi-coin"></i> Poin Saya
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('donasi.riwayat') }}"
               @if(request()->routeIs('donasi.riwayat') || request()->routeIs('donasi.show')) aria-current="page" @endif>
                <i class="bi bi-box-seam"></i> Riwayat Donasi
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('ttc.riwayat') }}"
               @if(request()->routeIs('ttc.riwayat') || request()->routeIs('ttc.show')) aria-current="page" @endif>
                <i class="bi bi-shop"></i> Riwayat Tap Try and Collect
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('wishlist.index') }}"
               @if(request()->routeIs('wishlist.*')) aria-current="page" @endif>
                <i class="bi bi-heart"></i> Wishlist
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('notifikasi.index') }}"
               @if(request()->routeIs('notifikasi.*')) aria-current="page" @endif>
                <i class="bi bi-bell"></i> Notifikasi
            </a>
        </li>
        <li>
            <a class="d-block py-2" href="{{ route('keluhan.index') }}"
               @if(request()->routeIs('keluhan.*')) aria-current="page" @endif>
                <i class="bi bi-chat-dots"></i> Keluhan
            </a>
        </li>
    </ul>

    <form class="mt-4" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="cb-tombol cb-tombol--garis cb-tombol--kecil cb-tombol--penuh">
            <i class="bi bi-box-arrow-right"></i> Keluar dari akun
        </button>
    </form>
</nav>
