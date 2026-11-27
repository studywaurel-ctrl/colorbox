@extends('layouts.app')

@section('judul', 'Lupa Kata Sandi')

@section('isi')
    <div class="cb-wadah">
        <div class="row justify-content-center cb-bagian">
            <div class="col-lg-5 col-md-7">
                <h1 class="cb-judul-halaman">Lupa Kata Sandi</h1>
                <p class="cb-isian__bantu">
                    Masukkan email yang dipakai saat mendaftar. Kami akan membuat tautan untuk
                    mengatur kata sandi baru.
                </p>

                <p class="cb-catatan cb-catatan--ingat">
                    Pada pemasangan ini pengaturan email memakai <strong>MAIL_MAILER=log</strong>,
                    sehingga email tidak benar-benar terkirim. Isi emailnya dicatat ke berkas
                    <code>storage/logs/laravel.log</code> &mdash; buka berkas tersebut, lalu salin
                    tautan atur ulang kata sandi yang muncul di bagian paling bawah.
                </p>

                <form class="cb-borang mt-4" action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="cb-isian">
                        <label class="cb-label" for="email">Email</label>
                        <input class="cb-masukan" type="email" id="email" name="email"
                               value="{{ old('email') }}" autocomplete="email" required>
                    </div>

                    <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--penuh">
                        Kirim tautan atur ulang
                    </button>
                </form>

                <hr>

                <p class="text-center mb-0" style="font-size: var(--cb-kecil)">
                    Ingat kata sandinya? <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
                </p>
            </div>
        </div>
    </div>
@endsection
