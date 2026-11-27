@extends('layouts.app')

@section('judul', 'Masuk ke Akun')

@section('isi')
    <div class="cb-wadah">
        <div class="row justify-content-center cb-bagian">
            <div class="col-lg-5 col-md-7">
                <h1 class="cb-judul-halaman">Masuk ke Akun</h1>
                <p class="cb-isian__bantu">
                    Masuk untuk menyelesaikan pembayaran, melihat riwayat pesanan, menjadwalkan
                    donasi, dan mengajukan retur.
                </p>

                <form class="cb-borang mt-4" action="{{ route('login.proses') }}" method="POST">
                    @csrf

                    <div class="cb-isian">
                        <label class="cb-label" for="email">Email</label>
                        <input class="cb-masukan" type="email" id="email" name="email"
                               value="{{ old('email') }}" autocomplete="email" required>
                    </div>

                    <div class="cb-isian">
                        <label class="cb-label" for="password">Kata sandi</label>
                        <input class="cb-masukan" type="password" id="password" name="password"
                               autocomplete="current-password" required>
                    </div>

                    <div class="cb-isian">
                        <label class="cb-pilihan__label" for="ingat">
                            <input type="checkbox" id="ingat" name="ingat" value="1"
                                   @if(old('ingat')) checked @endif>
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--penuh">Masuk</button>
                </form>

                <p class="mt-3" style="font-size: var(--cb-kecil)">
                    <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                </p>

                <hr>

                <p class="text-center mb-0" style="font-size: var(--cb-kecil)">
                    Belum punya akun Colorbox?
                    <a href="{{ route('register') }}">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
@endsection
