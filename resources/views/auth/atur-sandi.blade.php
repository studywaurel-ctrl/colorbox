@extends('layouts.app')

@section('judul', 'Atur Kata Sandi Baru')

@section('isi')
    <div class="cb-wadah">
        <div class="row justify-content-center cb-bagian">
            <div class="col-lg-5 col-md-7">
                <h1 class="cb-judul-halaman">Atur Kata Sandi Baru</h1>
                <p class="cb-isian__bantu">
                    Halaman ini dibuka dari tautan atur ulang kata sandi. Tautannya berlaku
                    selama 60 menit sejak dibuat.
                </p>

                <p class="cb-catatan cb-catatan--ingat">
                    Tautan atur ulang dicatat ke berkas <code>storage/logs/laravel.log</code>
                    karena email pada pemasangan ini memakai <strong>MAIL_MAILER=log</strong>.
                </p>

                <form class="cb-borang mt-4" action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="cb-isian">
                        <label class="cb-label" for="email">Email</label>
                        <input class="cb-masukan" type="email" id="email" name="email"
                               value="{{ old('email', $email) }}" autocomplete="email" required>
                    </div>

                    <div class="cb-isian">
                        <label class="cb-label" for="password">Kata sandi baru</label>
                        <input class="cb-masukan" type="password" id="password" name="password"
                               autocomplete="new-password" required>
                        <span class="cb-isian__bantu">Paling sedikit 8 karakter.</span>
                    </div>

                    <div class="cb-isian">
                        <label class="cb-label" for="password_confirmation">Ulangi kata sandi baru</label>
                        <input class="cb-masukan" type="password" id="password_confirmation"
                               name="password_confirmation" autocomplete="new-password" required>
                    </div>

                    <button type="submit" class="cb-tombol cb-tombol--utama cb-tombol--penuh">
                        Simpan kata sandi baru
                    </button>
                </form>

                <hr>

                <p class="text-center mb-0" style="font-size: var(--cb-kecil)">
                    <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
                </p>
            </div>
        </div>
    </div>
@endsection
