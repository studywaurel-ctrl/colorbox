@extends('layouts.app')

@section('judul', 'Profil dan Kata Sandi')

@section('isi')
    <div class="cb-wadah cb-bagian">
        <div class="cb-judul-halaman">
            <ol class="cb-remah">
                <li><a href="{{ route('beranda') }}">Beranda</a></li>
                <li><a href="{{ route('akun.index') }}">Akun Saya</a></li>
                <li>Profil dan Kata Sandi</li>
            </ol>
            <h1>Profil dan Kata Sandi</h1>
        </div>

        <div class="row g-5">
            <div class="col-lg-3">
                @include('akun.menu')
            </div>

            <div class="col-lg-9">
                {{-- Data diri --}}
                <section class="cb-panel mb-4">
                    <div class="cb-kepala-bagian">
                        <h2>Data Diri</h2>
                    </div>

                    @if($pengguna->photo)
                        <p class="mb-3">
                            <img src="{{ asset($pengguna->photo) }}" alt="Foto profil {{ $pengguna->name }}"
                                 width="88" height="88" style="border-radius: 50%; object-fit: cover">
                        </p>
                    @endif

                    <form class="cb-borang" action="{{ route('profil.update') }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="cb-isian">
                            <label class="cb-label" for="name">Nama lengkap</label>
                            <input class="cb-masukan" type="text" id="name" name="name"
                                   value="{{ old('name', $pengguna->name) }}" required>
                        </div>

                        <div class="cb-isian">
                            <label class="cb-label" for="email">Email</label>
                            <input class="cb-masukan" type="email" id="email"
                                   value="{{ $pengguna->email }}" disabled>
                            <span class="cb-isian__bantu">Email tidak dapat diubah sendiri. Hubungi tim Colorbox bila perlu diganti.</span>
                        </div>

                        <div class="cb-baris-isian cb-baris-isian--dua">
                            <div class="cb-isian">
                                <label class="cb-label" for="phone">Nomor telepon</label>
                                <input class="cb-masukan" type="tel" id="phone" name="phone"
                                       value="{{ old('phone', $pengguna->phone) }}" placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="cb-isian">
                                <label class="cb-label" for="birth_date">Tanggal lahir</label>
                                <input class="cb-masukan" type="date" id="birth_date" name="birth_date"
                                       value="{{ old('birth_date', $pengguna->birth_date ? $pengguna->birth_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div class="cb-isian">
                            <label class="cb-label" for="gender">Jenis kelamin</label>
                            <select class="cb-masukan" id="gender" name="gender">
                                <option value="">Tidak diisi</option>
                                <option value="perempuan" @if(old('gender', $pengguna->gender) === 'perempuan') selected @endif>Perempuan</option>
                                <option value="laki-laki" @if(old('gender', $pengguna->gender) === 'laki-laki') selected @endif>Laki-laki</option>
                            </select>
                        </div>

                        <div class="cb-isian">
                            <label class="cb-label" for="photo">Foto profil</label>
                            <input class="cb-masukan" type="file" id="photo" name="photo" accept="image/*">
                            <span class="cb-isian__bantu">Berupa gambar, ukuran paling besar 2 MB. Kosongkan bila tidak ingin mengganti.</span>
                        </div>

                        <div>
                            <button type="submit" class="cb-tombol cb-tombol--utama">Simpan perubahan</button>
                        </div>
                    </form>
                </section>

                {{-- Ganti kata sandi --}}
                <section class="cb-panel">
                    <div class="cb-kepala-bagian">
                        <h2>Ganti Kata Sandi</h2>
                    </div>

                    <form class="cb-borang" action="{{ route('sandi.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="cb-isian">
                            <label class="cb-label" for="current_password">Kata sandi saat ini</label>
                            <input class="cb-masukan" type="password" id="current_password"
                                   name="current_password" autocomplete="current-password" required>
                        </div>

                        <div class="cb-baris-isian cb-baris-isian--dua">
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
                        </div>

                        <div>
                            <button type="submit" class="cb-tombol cb-tombol--utama">Ganti kata sandi</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
@endsection
